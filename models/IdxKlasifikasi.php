<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "idx_klasifikasi".
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property int $level
 * @property int|null $parent_id
 * @property int|null $sektor_id
 * @property string|null $deskripsi
 * @property int $urutan
 * @property int $aktif
 * @property string $created_at
 * @property string $updated_at
 *
 * @property IdxKlasifikasi $parent
 * @property IdxKlasifikasi[] $children
 * @property Sektor $sektor
 * @property Perusahaan[] $perusahaans
 */
class IdxKlasifikasi extends \yii\db\ActiveRecord
{
    const LEVEL_SEKTOR = 1;
    const LEVEL_SUBSEKTOR = 2;
    const LEVEL_INDUSTRI = 3;
    const LEVEL_SUBINDUSTRI = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'idx_klasifikasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode', 'nama', 'level'], 'required'],
            [['level', 'parent_id', 'sektor_id', 'urutan', 'aktif'], 'integer'],
            [['deskripsi'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode'], 'string', 'max' => 10],
            [['nama'], 'string', 'max' => 150],
            [['kode'], 'unique'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => self::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['sektor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sektor::className(), 'targetAttribute' => ['sektor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'nama' => 'Nama',
            'level' => 'Level',
            'parent_id' => 'Parent',
            'sektor_id' => 'Sektor Root',
            'deskripsi' => 'Deskripsi',
            'urutan' => 'Urutan',
            'aktif' => 'Aktif',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[IdxKlasifikasi|parent]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[IdxKlasifikasi|children]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[Sektor|sektor]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSektor()
    {
        return $this->hasOne(Sektor::className(), ['id' => 'sektor_id']);
    }

    /**
     * Gets query for [[Perusahaan|perusahaans]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerusahaans()
    {
        return $this->hasMany(Perusahaan::className(), ['idx_klasifikasi_id' => 'id']);
    }

    /**
     * Get level label
     *
     * @return string
     */
    public function getLevelLabel()
    {
        $labels = [
            self::LEVEL_SEKTOR => 'Sektor',
            self::LEVEL_SUBSEKTOR => 'Subsektor',
            self::LEVEL_INDUSTRI => 'Industri',
            self::LEVEL_SUBINDUSTRI => 'Subindustri',
        ];
        return $labels[$this->level] ?? 'Unknown';
    }

    /**
     * Get full path (breadcrumb)
     *
     * @return string
     */
    public function getFullPath()
    {
        $path = [];
        $current = $this;
        while ($current) {
            array_unshift($path, $current->nama);
            $current = $current->parent;
        }
        return implode(' > ', $path);
    }

    /**
     * Get dropdown options grouped by sector
     *
     * @return array
     */
    public static function getGroupedDropdownList()
    {
        $sektors = Sektor::find()
            ->where(['aktif' => true])
            ->orderBy(['urutan' => SORT_ASC])
            ->with(['idxKlasifikasis' => function ($q) {
                $q->where(['level' => self::LEVEL_SUBSEKTOR, 'aktif' => true])
                  ->orderBy(['urutan' => SORT_ASC])
                  ->with(['children' => function ($q2) {
                      $q2->where(['level' => self::LEVEL_INDUSTRI, 'aktif' => true])
                         ->orderBy(['urutan' => SORT_ASC])
                         ->with(['children' => function ($q3) {
                             $q3->where(['level' => self::LEVEL_SUBINDUSTRI, 'aktif' => true])
                                ->orderBy(['urutan' => SORT_ASC]);
                         }]);
                  }]);
            }])
            ->all();

        $result = [];
        foreach ($sektors as $sektor) {
            $items = [];
            foreach ($sektor->idxKlasifikasis as $subsektor) {
                foreach ($subsektor->children as $industri) {
                    foreach ($industri->children as $subindustri) {
                        $items[$subindustri->id] = sprintf('%s > %s > %s > %s', 
                            $sektor->nama, $subsektor->nama, $industri->nama, $subindustri->nama);
                    }
                    // Also add industry level if no subindustry
                    if (empty($industri->children)) {
                        $items[$industri->id] = sprintf('%s > %s > %s', 
                            $sektor->nama, $subsektor->nama, $industri->nama);
                    }
                }
                // Also add subsector level if no industry
                if (empty($subsektor->children)) {
                    $items[$subsektor->id] = sprintf('%s > %s', $sektor->nama, $subsektor->nama);
                }
            }
            if (!empty($items)) {
                $result[$sektor->nama] = $items;
            }
        }
        return $result;
    }

    /**
     * Get flat list for simple dropdown
     *
     * @return array
     */
    public static function getFlatDropdownList($level = null)
    {
        $query = self::find()
            ->where(['aktif' => true]);
        
        if ($level) {
            $query->andWhere(['level' => $level]);
        }
        
        return ArrayHelper::map($query->orderBy(['level' => SORT_ASC, 'urutan' => SORT_ASC, 'nama' => SORT_ASC])->all(), 'id', function ($model) {
            return sprintf('[%s] %s', $model->getLevelLabel(), $model->getFullPath());
        });
    }

    /**
     * Find by full path
     *
     * @param string $path e.g. "Teknologi > Perangkat Lunak & Jasa TI > Aplikasi & Jasa Internet"
     * @return self|null
     */
    public static function findByPath($path)
    {
        $parts = explode(' > ', $path);
        $parent = null;
        
        foreach ($parts as $i => $part) {
            $expectedLevel = $i + 2; // Level 2 = Subsektor
            $model = self::find()
                ->where(['nama' => $part, 'level' => $expectedLevel])
                ->andWhere($parent ? ['parent_id' => $parent->id] : ['parent_id' => null])
                ->one();
            
            if (!$model) {
                return null;
            }
            $parent = $model;
        }
        
        return $parent;
    }
}