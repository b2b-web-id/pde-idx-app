<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Canonical owner entity used by ownership snapshots.
 */
class Entitas extends ActiveRecord
{
    public const TIPE_INDIVIDU = 'individu';
    public const TIPE_PERUSAHAAN = 'perusahaan';
    public const TIPE_KELOMPOK = 'kelompok';
    public const TIPE_TREASURY = 'treasury';
    public const TIPE_EKSTERNAL = 'eksternal';

    public static function tableName()
    {
        return '{{%entitas}}';
    }

    public function rules()
    {
        return [
            [['tipe', 'nama_display'], 'required'],
            [['individu_id', 'perusahaan_id'], 'integer'],
            [['tipe'], 'in', 'range' => array_keys(self::getTipeOptions())],
            [['tipe', 'kode_eksternal', 'identifier_type'], 'string', 'max' => 30],
            [['nama_display'], 'string', 'max' => 200],
            [['identifier_value'], 'string', 'max' => 100],
            [['individu_id'], 'exist', 'targetClass' => Individu::className(), 'targetAttribute' => ['individu_id' => 'ID']],
            [['perusahaan_id'], 'exist', 'targetClass' => Perusahaan::className(), 'targetAttribute' => ['perusahaan_id' => 'ID']],
        ];
    }

    public static function getTipeOptions()
    {
        return [
            self::TIPE_INDIVIDU => 'Individu',
            self::TIPE_PERUSAHAAN => 'Perusahaan',
            self::TIPE_KELOMPOK => 'Kelompok',
            self::TIPE_TREASURY => 'Saham Treasury',
            self::TIPE_EKSTERNAL => 'Eksternal',
        ];
    }

    public function getIndividu()
    {
        return $this->hasOne(Individu::className(), ['ID' => 'individu_id']);
    }

    public function getPerusahaan()
    {
        return $this->hasOne(Perusahaan::className(), ['ID' => 'perusahaan_id']);
    }

    public function getKepemilikan()
    {
        return $this->hasMany(KepemilikanPerusahaan::className(), ['pemilik_entitas_id' => 'id']);
    }

    public static function getOwnerOptions()
    {
        return static::find()->orderBy(['nama_display' => SORT_ASC])->all();
    }

    public static function syncFromIndividu(Individu $individu)
    {
        return self::syncCanonical(
            self::TIPE_INDIVIDU,
            $individu->NAMA,
            ['individu_id' => $individu->ID, 'perusahaan_id' => null]
        );
    }

    public static function syncFromPerusahaan(Perusahaan $perusahaan)
    {
        return self::syncCanonical(
            self::TIPE_PERUSAHAAN,
            $perusahaan->NAMA,
            ['perusahaan_id' => $perusahaan->ID, 'individu_id' => null]
        );
    }

    private static function syncCanonical($tipe, $nama, array $keys)
    {
        $entity = self::findOne($keys);
        if ($entity === null) {
            $entity = new self();
            $entity->setAttributes($keys);
        }

        $entity->tipe = $tipe;
        $entity->nama_display = $nama;
        return $entity->save(false) ? $entity : null;
    }

    public function getDisplayName()
    {
        return $this->nama_display;
    }
}
