<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sektor".
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property string|null $deskripsi
 * @property int $urutan
 * @property int $aktif
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Kbli[] $kblis
 * @property Perusahaan[] $perusahaans
 * @property IdxKlasifikasi[] $idxKlasifikasis
 */
class Sektor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sektor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode', 'nama'], 'required'],
            [['deskripsi'], 'string'],
            [['urutan', 'aktif'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode'], 'string', 'max' => 10],
            [['nama'], 'string', 'max' => 100],
            [['kode'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode Sektor',
            'nama' => 'Nama Sektor',
            'deskripsi' => 'Deskripsi',
            'urutan' => 'Urutan',
            'aktif' => 'Aktif',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Kbli|kblis]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKblis()
    {
        return $this->hasMany(Kbli::className(), ['sektor_id' => 'id']);
    }

    /**
     * Gets query for [[Perusahaan|perusahaans]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerusahaans()
    {
        return $this->hasMany(Perusahaan::className(), ['sektor_id' => 'id']);
    }

    /**
     * Gets query for [[IdxKlasifikasi|idxKlasifikasis]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdxKlasifikasis()
    {
        return $this->hasMany(IdxKlasifikasi::className(), ['sektor_id' => 'id']);
    }

    /**
     * Get dropdown options for sektor
     *
     * @return array
     */
    public static function getDropdownOptions()
    {
        return static::find()
            ->where(['aktif' => 1])
            ->orderBy(['urutan' => SORT_ASC, 'nama' => SORT_ASC])
            ->all();
    }

    /**
     * Get list for dropdown (id => nama)
     *
     * @return array
     */
    public static function getList()
    {
        return ArrayHelper::map(self::getDropdownOptions(), 'id', 'nama');
    }
}