<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "biro_admin_efek".
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property string|null $alamat
 * @property string|null $telepon
 * @property string|null $email
 * @property int $aktif
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Perusahaan[] $perusahaans
 */
class BiroAdminEfek extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'biro_admin_efek';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode', 'nama'], 'required'],
            [['alamat'], 'string'],
            [['aktif'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode'], 'string', 'max' => 10],
            [['nama'], 'string', 'max' => 150],
            [['telepon'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
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
            'kode' => 'Kode',
            'nama' => 'Nama Biro Admin Efek',
            'alamat' => 'Alamat',
            'telepon' => 'Telepon',
            'email' => 'Email',
            'aktif' => 'Aktif',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Perusahaan|perusahaans]] relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerusahaans()
    {
        return $this->hasMany(Perusahaan::className(), ['biro_admin_efek_id' => 'id']);
    }

    /**
     * Get dropdown list
     *
     * @return array
     */
    public static function getDropdownList()
    {
        return ArrayHelper::map(
            self::find()->where(['aktif' => 1])->orderBy('nama')->all(),
            'id',
            function ($model) {
                return sprintf('%s (%s)', $model->nama, $model->kode);
            }
        );
    }
}