<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "perusahaan".
 *
 * @property int $ID
 * @property string $NAMA
 * @property string|null $IDX_KODE
 * @property string $ALAMAT
 * @property string|null $EMAIL
 * @property string|null $TELEPON
 * @property string|null $FAKS
 * @property string|null $NPWP
 * @property string|null $SITUS
 * @property string|null $TANGGAL_AKTA
 * @property string|null $USAHA_UTAMA
 * @property string|null $SEKTOR
 * @property string|null $KODE_KBLI
 * @property string|null $papan_pencatatan
 * @property string|null $tanggal_pencatatan
 * @property int|null $idx_klasifikasi_id
 * @property int|null $biro_admin_efek_id
 * @property int|null $sektor_id
 * @property int|null $kbli_id
 * @property string $TANGGAL_REKAM
 *
 * @property Sektor $sektor
 * @property Kbli $kbli
 * @property IdxKlasifikasi $idxKlasifikasi
 * @property BiroAdminEfek $biroAdminEfek
 * @property IndividuPerusahaan[] $individuPerusahaans
 * @property Individu[] $individus
 */
class Perusahaan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'perusahaan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NAMA', 'ALAMAT'], 'required'],
            [['TANGGAL_AKTA', 'TANGGAL_REKAM', 'tanggal_pencatatan'], 'safe'],
            [['sektor_id', 'kbli_id', 'idx_klasifikasi_id', 'biro_admin_efek_id'], 'integer'],
            [['NAMA'], 'string', 'max' => 200],
            [['IDX_KODE'], 'string', 'max' => 4],
            [['ALAMAT', 'USAHA_UTAMA', 'SEKTOR'], 'string', 'max' => 250],
            [['EMAIL', 'TELEPON', 'FAKS'], 'string', 'max' => 50],
            [['NPWP'], 'string', 'max' => 20],
            [['SITUS'], 'string', 'max' => 100],
            [['KODE_KBLI'], 'string', 'max' => 5],
            [['papan_pencatatan'], 'string', 'max' => 20],
            [['NAMA'], 'unique'],
            [['sektor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sektor::className(), 'targetAttribute' => ['sektor_id' => 'id']],
            [['kbli_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kbli::className(), 'targetAttribute' => ['kbli_id' => 'id']],
            [['idx_klasifikasi_id'], 'exist', 'skipOnError' => true, 'targetClass' => IdxKlasifikasi::className(), 'targetAttribute' => ['idx_klasifikasi_id' => 'id']],
            [['biro_admin_efek_id'], 'exist', 'skipOnError' => true, 'targetClass' => BiroAdminEfek::className(), 'targetAttribute' => ['biro_admin_efek_id' => 'id']],
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
     * Gets query for [[Kbli|kbli]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKbli()
    {
        return $this->hasOne(Kbli::className(), ['id' => 'kbli_id']);
    }

    /**
     * Gets query for [[IdxKlasifikasi|idxKlasifikasi]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdxKlasifikasi()
    {
        return $this->hasOne(IdxKlasifikasi::className(), ['id' => 'idx_klasifikasi_id']);
    }

    /**
     * Gets query for [[BiroAdminEfek|biroAdminEfek]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBiroAdminEfek()
    {
        return $this->hasOne(BiroAdminEfek::className(), ['id' => 'biro_admin_efek_id']);
    }

    /**
     * Gets query for [[IndividuPerusahaan|individuPerusahaans]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndividuPerusahaans()
    {
        return $this->hasMany(IndividuPerusahaan::className(), ['PERUSAHAAN_ID' => 'ID']);
    }

    /**
     * Gets query for [[Individu|individus]] relation via IndividuPerusahaan.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndividus()
    {
        return $this->hasMany(Individu::className(), ['ID' => 'INDIVIDU_ID'])
            ->via('individuPerusahaans');
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'NAMA' => 'Nama',
            'IDX_KODE' => 'Idx Kode',
            'ALAMAT' => 'Alamat',
            'EMAIL' => 'Email',
            'TELEPON' => 'Telepon',
            'FAKS' => 'Faks',
            'NPWP' => 'Npwp',
            'SITUS' => 'Situs',
            'TANGGAL_AKTA' => 'Tanggal Akta',
            'USAHA_UTAMA' => 'Usaha Utama',
            'SEKTOR' => 'Sektor (Legacy)',
            'KODE_KBLI' => 'Kode KBLI (Legacy)',
            'sektor_id' => 'Sektor',
            'kbli_id' => 'KBLI',
            'papan_pencatatan' => 'Papan Pencatatan',
            'tanggal_pencatatan' => 'Tanggal Pencatatan',
            'idx_klasifikasi_id' => 'Subsektor / Industri / Subindustri',
            'biro_admin_efek_id' => 'Biro Administrasi Efek',
            'TANGGAL_REKAM' => 'Tanggal Rekam',
        ];
    }
}
