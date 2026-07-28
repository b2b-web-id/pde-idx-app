<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "individu".
 *
 * @property int $ID
 * @property string $NAMA
 * @property string|null $ALAMAT
 * @property string|null $EMAIL
 * @property string|null $TELEPON
 * @property string|null $HP
 * @property string|null $FAKS
 * @property string|null $SITUS
 * @property string|null $TANGGAL_LAHIR
 * @property string|null $TEMPAT_LAHIR
 * @property string $TANGGAL_UPDATE
 */
class Individu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'individu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NAMA'], 'required'],
            [['TANGGAL_LAHIR', 'TANGGAL_UPDATE'], 'safe'],
            [['NAMA'], 'string', 'max' => 200],
            [['ALAMAT'], 'string', 'max' => 250],
            [['EMAIL', 'SITUS', 'TEMPAT_LAHIR'], 'string', 'max' => 100],
            [['TELEPON', 'HP', 'FAKS'], 'string', 'max' => 50],
            [['NAMA'], 'unique'],
        ];
    }

    // ... di bagian bawah file, sebelum penutup tag PHP

    /**
     * Gets query for [[IndividuPerusahaan|individuPerusahaans]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndividuPerusahaans()
    {
        return $this->hasMany(IndividuPerusahaan::className(), ['INDIVIDU_ID' => 'ID']);
    }

    /**
     * Gets query for [[Perusahaan|perusahaans]] relation via IndividuPerusahaan.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerusahaans()
    {
        return $this->hasMany(Perusahaan::className(), ['ID' => 'PERUSAHAAN_ID'])
            ->via('individuPerusahaans');
    }

    public function getEntitas()
    {
        return $this->hasOne(Entitas::className(), ['individu_id' => 'ID']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Entitas::syncFromIndividu($this);
    }
}
