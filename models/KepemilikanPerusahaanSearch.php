<?php

namespace app\models;

use yii\data\ActiveDataProvider;

class KepemilikanPerusahaanSearch extends KepemilikanPerusahaan
{
    public function rules()
    {
        return [
            [['id', 'pemilik_id', 'perusahaan_id', 'jumlah_saham'], 'integer'],
            [['persentase_kepemilikan', 'persentase_hak_suara'], 'number'],
            [['jenis_kepemilikan', 'status_kontrol', 'sumber_data', 'tanggal_data'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = KepemilikanPerusahaan::find()->joinWith(['pemilik', 'perusahaan']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'attributes' => [
                    'tanggal_data',
                    'persentase_kepemilikan',
                    'pemilik.NAMA' => [
                        'asc' => ['pemilik.NAMA' => SORT_ASC],
                        'desc' => ['pemilik.NAMA' => SORT_DESC],
                        'label' => 'Perusahaan Pemilik',
                    ],
                    'perusahaan.NAMA' => [
                        'asc' => ['target_perusahaan.NAMA' => SORT_ASC],
                        'desc' => ['target_perusahaan.NAMA' => SORT_DESC],
                        'label' => 'Perusahaan Target',
                    ],
                ],
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'kepemilikan_perusahaan.id' => $this->id,
            'kepemilikan_perusahaan.pemilik_id' => $this->pemilik_id,
            'kepemilikan_perusahaan.perusahaan_id' => $this->perusahaan_id,
            'kepemilikan_perusahaan.jenis_kepemilikan' => $this->jenis_kepemilikan,
            'kepemilikan_perusahaan.tanggal_data' => $this->tanggal_data,
        ]);

        return $dataProvider;
    }
}
