<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "kbli".
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property string|null $kelompok
 * @property string|null $golongan
 * @property string|null $bidang
 * @property string|null $deskripsi
 * @property int|null $sektor_id
 * @property int $aktif
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Sektor $sektor
 * @property Perusahaan[] $perusahaans
 */
class Kbli extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kbli';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode', 'nama'], 'required'],
            [['deskripsi'], 'string'],
            [['sektor_id', 'aktif'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode'], 'string', 'max' => 5],
            [['nama'], 'string', 'max' => 255],
            [['kelompok'], 'string', 'max' => 3],
            [['golongan'], 'string', 'max' => 2],
            [['bidang'], 'string', 'max' => 1],
            [['kode'], 'unique'],
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
            'kode' => 'Kode KBLI',
            'nama' => 'Nama Usaha',
            'kelompok' => 'Kelompok (3 digit)',
            'golongan' => 'Golongan (2 digit)',
            'bidang' => 'Bidang (1 huruf)',
            'deskripsi' => 'Deskripsi',
            'sektor_id' => 'Sektor',
            'aktif' => 'Aktif',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
        return $this->hasMany(Perusahaan::className(), ['kbli_id' => 'id']);
    }

    /**
     * Get KBLI list for dropdown (id => "KODE - NAMA")
     *
     * @param int|null $sektorId Optional filter by sektor
     * @return array
     */
    public static function getDropdownList($sektorId = null)
    {
        $query = static::find()
            ->where(['aktif' => 1])
            ->orderBy(['kode' => SORT_ASC]);

        if ($sektorId) {
            $query->andWhere(['sektor_id' => $sektorId]);
        }

        $models = $query->all();
        return ArrayHelper::map($models, 'id', function ($model) {
            return sprintf('%s - %s', $model->kode, $model->nama);
        });
    }

    /**
     * Get KBLI grouped by sektor for optgroup dropdown
     *
     * @return array
     */
    public static function getGroupedDropdownList()
    {
        $sektors = Sektor::find()
            ->where(['aktif' => 1])
            ->orderBy(['urutan' => SORT_ASC])
            ->with(['kblis' => function ($q) {
                $q->where(['aktif' => 1])->orderBy(['kode' => SORT_ASC]);
            }])
            ->all();

        $result = [];
        foreach ($sektors as $sektor) {
            $items = [];
            foreach ($sektor->kblis as $kbli) {
                $items[$kbli->id] = sprintf('%s - %s', $kbli->kode, $kbli->nama);
            }
            if (!empty($items)) {
                $result[$sektor->nama] = $items;
            }
        }
        return $result;
    }

    /**
     * Find by kode
     *
     * @param string $kode
     * @return static|null
     */
    public static function findByKode($kode)
    {
        return static::findOne(['kode' => $kode, 'aktif' => 1]);
    }
}