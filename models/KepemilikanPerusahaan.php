<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Company-to-company ownership snapshot.
 *
 * A subsidiary is represented by a record where the owner is a company and
 * the target is the subsidiary company. Historical snapshots remain distinct
 * through tanggal_data and sumber_data.
 */
class KepemilikanPerusahaan extends ActiveRecord
{
    public const JENIS_LANGSUNG = 'langsung';
    public const JENIS_TIDAK_LANGSUNG = 'tidak_langsung';

    public static function tableName()
    {
        return '{{%kepemilikan_perusahaan}}';
    }

    public function rules()
    {
        return [
            [['pemilik_id', 'perusahaan_id', 'tanggal_data'], 'required'],
            [['pemilik_id', 'perusahaan_id', 'jumlah_saham'], 'integer'],
            [['persentase_kepemilikan', 'persentase_hak_suara'], 'number', 'min' => 0, 'max' => 100],
            [['tanggal_data', 'berlaku_mulai', 'berlaku_sampai', 'created_at', 'updated_at'], 'safe'],
            [['jenis_kepemilikan'], 'in', 'range' => array_keys(self::getJenisKepemilikanOptions())],
            [['jenis_kepemilikan'], 'string', 'max' => 30],
            [['status_kontrol'], 'string', 'max' => 30],
            [['sumber_data'], 'string', 'max' => 100],
            [['referensi_data'], 'string', 'max' => 255],
            [['pemilik_id'], 'exist', 'targetClass' => Perusahaan::className(), 'targetAttribute' => ['pemilik_id' => 'ID']],
            [['perusahaan_id'], 'exist', 'targetClass' => Perusahaan::className(), 'targetAttribute' => ['perusahaan_id' => 'ID']],
            [['pemilik_id', 'perusahaan_id', 'tanggal_data', 'sumber_data'], 'unique', 'targetAttribute' => ['pemilik_id', 'perusahaan_id', 'tanggal_data', 'sumber_data']],
            [['perusahaan_id'], 'validateDistinctCompanies'],
            [['berlaku_sampai'], 'compare', 'compareAttribute' => 'berlaku_mulai', 'operator' => '>=', 'when' => function ($model) {
                return $model->berlaku_sampai && $model->berlaku_mulai;
            }],
        ];
    }

    public function validateDistinctCompanies($attribute)
    {
        if ((int) $this->pemilik_id === (int) $this->perusahaan_id && $this->pemilik_id !== null) {
            $this->addError($attribute, 'Perusahaan pemilik dan perusahaan target harus berbeda.');
        }
    }

    public function attributeLabels()
    {
        return [
            'pemilik_id' => 'Perusahaan Pemilik',
            'perusahaan_id' => 'Perusahaan Target',
            'jumlah_saham' => 'Jumlah Saham',
            'persentase_kepemilikan' => 'Persentase Kepemilikan',
            'persentase_hak_suara' => 'Persentase Hak Suara',
            'jenis_kepemilikan' => 'Jenis Kepemilikan',
            'status_kontrol' => 'Status Kontrol',
            'berlaku_mulai' => 'Berlaku Mulai',
            'berlaku_sampai' => 'Berlaku Sampai',
            'tanggal_data' => 'Tanggal Data',
            'sumber_data' => 'Sumber Data',
            'referensi_data' => 'Referensi Data',
        ];
    }

    public static function getJenisKepemilikanOptions()
    {
        return [
            self::JENIS_LANGSUNG => 'Langsung',
            self::JENIS_TIDAK_LANGSUNG => 'Tidak Langsung',
        ];
    }

    public function getPemilik()
    {
        return $this->hasOne(Perusahaan::className(), ['ID' => 'pemilik_id'])
            ->from(['pemilik' => Perusahaan::tableName()]);
    }

    public function getPerusahaan()
    {
        return $this->hasOne(Perusahaan::className(), ['ID' => 'perusahaan_id'])
            ->from(['target_perusahaan' => Perusahaan::tableName()]);
    }

    public static function findCurrent()
    {
        return static::find()->orderBy(['tanggal_data' => SORT_DESC]);
    }
}
