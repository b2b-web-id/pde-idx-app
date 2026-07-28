<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "individu_perusahaan".
 *
 * @property int $ID
 * @property int $INDIVIDU_ID
 * @property int $PERUSAHAAN_ID
 * @property string|null $JABATAN
 * @property string|null $TANGGAL_MULAI
 * @property string|null $TANGGAL_AKHIR
 * @property string $STATUS
 * @property string|null $KETERANGAN
 * @property string $CREATED_AT
 * @property string $UPDATED_AT
 *
 * @property Individu $individu
 * @property Perusahaan $perusahaan
 */
class IndividuPerusahaan extends \yii\db\ActiveRecord
{
    // Konstanta jabatan standar POJK / IDX
    public const JABATAN_PRESIDEN_DIREKSI = 'Presiden Direksi';
    public const JABATAN_DIREKTUR_UTAMA = 'Direktur Utama';
    public const JABATAN_DIREKTUR = 'Direktur';
    public const JABATAN_KOMISARIS_UTAMA = 'Komisaris Utama';
    public const JABATAN_KOMISARIS = 'Komisaris';
    public const JABATAN_KOMISARIS_INDEPENDEN = 'Komisaris Independen';
    public const JABATAN_SEKRETARIS_PERUSAHAAN = 'Sekretaris Perusahaan';
    public const JABATAN_KEPALA_AUDIT_INTERNAL = 'Kepala Audit Internal';
    public const JABATAN_KEPALA_KEPATUHAN = 'Kepala Kepatuhan';
    public const JABATAN_KEPALA_RISIKO = 'Kepala Risiko';
    public const JABATAN_PENGURUS_LAIN = 'Pengurus Lain';
    public const JABATAN_PEMGANG_SAHAM_UTAMA = 'Pemegang Saham Utama';
    public const JABATAN_PEMGANG_SAHAM_PENGENDALI = 'Pemegang Saham Pengendali';
    public const JABATAN_LAINNYA = 'Lainnya';

    /**
     * Daftar jabatan standar untuk dropdown
     *
     * @return array
     */
    public static function getJabatanOptions()
    {
        return [
            self::JABATAN_PRESIDEN_DIREKSI => 'Presiden Direksi',
            self::JABATAN_DIREKTUR_UTAMA => 'Direktur Utama',
            self::JABATAN_DIREKTUR => 'Direktur',
            self::JABATAN_KOMISARIS_UTAMA => 'Komisaris Utama',
            self::JABATAN_KOMISARIS => 'Komisaris',
            self::JABATAN_KOMISARIS_INDEPENDEN => 'Komisaris Independen',
            self::JABATAN_SEKRETARIS_PERUSAHAAN => 'Sekretaris Perusahaan',
            self::JABATAN_KEPALA_AUDIT_INTERNAL => 'Kepala Audit Internal',
            self::JABATAN_KEPALA_KEPATUHAN => 'Kepala Kepatuhan',
            self::JABATAN_KEPALA_RISIKO => 'Kepala Risiko',
            self::JABATAN_PENGURUS_LAIN => 'Pengurus Lain',
            self::JABATAN_PEMGANG_SAHAM_UTAMA => 'Pemegang Saham Utama',
            self::JABATAN_PEMGANG_SAHAM_PENGENDALI => 'Pemegang Saham Pengendali',
            self::JABATAN_LAINNYA => 'Lainnya',
        ];
    }

    /**
     * Alias untuk getJabatanOptions (untuk form & grid filter)
     *
     * @return array
     */
    public static function getJabatanRefList()
    {
        return self::getJabatanOptions();
    }

    /**
     * Get label untuk jabatan_ref
     *
     * @param string $value
     * @return string
     */
    public static function getJabatanRefLabel($value)
    {
        $list = self::getJabatanOptions();
        return $list[$value] ?? $value;
    }

    /**
     * Status options
     *
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
            'berhenti' => 'Berhenti',
            'pindah' => 'Pindah',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'individu_perusahaan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['INDIVIDU_ID', 'PERUSAHAAN_ID'], 'required'],
            [['INDIVIDU_ID', 'PERUSAHAAN_ID'], 'integer'],
            [['TANGGAL_MULAI', 'TANGGAL_AKHIR', 'CREATED_AT', 'UPDATED_AT'], 'safe'],
            [['JABATAN'], 'string', 'max' => 100],
            ['jabatan_ref', 'in', 'range' => array_keys(self::getJabatanOptions()), 'message' => 'Jabatan referensi tidak valid.', 'skipOnEmpty' => true],
            [['STATUS'], 'string', 'max' => 20],
            ['STATUS', 'in', 'range' => array_keys(self::getStatusOptions())],
            [['KETERANGAN'], 'string', 'max' => 255],
            [['INDIVIDU_ID', 'PERUSAHAAN_ID'], 'unique', 'targetAttribute' => ['INDIVIDU_ID', 'PERUSAHAAN_ID']],
            [['INDIVIDU_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Individu::className(), 'targetAttribute' => ['INDIVIDU_ID' => 'ID']],
            [['PERUSAHAAN_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Perusahaan::className(), 'targetAttribute' => ['PERUSAHAAN_ID' => 'ID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'INDIVIDU_ID' => 'Individu',
            'PERUSAHAAN_ID' => 'Perusahaan',
            'JABATAN' => 'Jabatan',
            'TANGGAL_MULAI' => 'Tanggal Mulai',
            'TANGGAL_AKHIR' => 'Tanggal Akhir',
            'STATUS' => 'Status',
            'KETERANGAN' => 'Keterangan',
            'CREATED_AT' => 'Created At',
            'UPDATED_AT' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Individu|individu]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndividu()
    {
        return $this->hasOne(Individu::className(), ['ID' => 'INDIVIDU_ID']);
    }

    /**
     * Gets query for [[Perusahaan|perusahaan]] relation.
     *
     * @return \yyi\db\ActiveQuery
     */
    public function getPerusahaan()
    {
        return $this->hasOne(Perusahaan::className(), ['ID' => 'PERUSAHAAN_ID']);
    }

    /**
     * Gets active relationships only.
     *
     * @return \yii\db\ActiveQuery
     */
    public static function findActive()
    {
        return static::find()->where(['STATUS' => 'aktif']);
    }

    /**
     * Check if jabatan adalah komisaris independen
     *
     * @return bool
     */
    public function isKomisarisIndependen()
    {
        return $this->JABATAN === self::JABATAN_KOMISARIS_INDEPENDEN;
    }

    /**
     * Check if jabatan adalah direksi
     *
     * @return bool
     */
    public function isDireksi()
    {
        $direksiJabatan = [
            self::JABATAN_PRESIDEN_DIREKSI,
            self::JABATAN_DIREKTUR_UTAMA,
            self::JABATAN_DIREKTUR,
        ];
        return in_array($this->JABATAN, $direksiJabatan);
    }

    /**
     * Check if jabatan adalah komisaris (termasuk independen)
     *
     * @return bool
     */
    public function isKomisaris()
    {
        $komisarisJabatan = [
            self::JABATAN_KOMISARIS_UTAMA,
            self::JABATAN_KOMISARIS,
            self::JABATAN_KOMISARIS_INDEPENDEN,
        ];
        return in_array($this->JABATAN, $komisarisJabatan);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Auto-sync: jika jabatan_ref adalah Komisaris Independen, set independen = true
        if ($this->jabatan_ref === self::JABATAN_KOMISARIS_INDEPENDEN) {
            $this->independen = true;
        }
        // Auto-sync: jika independen = true, set jabatan_ref ke Komisaris Independen
        if ($this->independen && $this->jabatan_ref !== self::JABATAN_KOMISARIS_INDEPENDEN) {
            $this->jabatan_ref = self::JABATAN_KOMISARIS_INDEPENDEN;
        }

        return true;
    }
}