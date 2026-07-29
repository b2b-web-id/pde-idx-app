<?php

namespace app\models;

use yii\data\ActiveDataProvider;

class KepemilikanPerusahaanSearch extends KepemilikanPerusahaan
{
    public $pemilikNama;
    public $targetNama;
    public function rules()
    {
        return [
            [['id', 'pemilik_entitas_id', 'perusahaan_id', 'jumlah_saham'], 'integer'],
            [['persentase_kepemilikan', 'persentase_hak_suara'], 'number'],
            [['jenis_kepemilikan', 'status_kontrol', 'sumber_data', 'tanggal_data', 'pemilikNama', 'targetNama'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = KepemilikanPerusahaan::find()->joinWith(['pemilikEntitas', 'perusahaan']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'attributes' => [
                    'tanggal_data',
                    'persentase_kepemilikan',
                    'pemilikEntitas.nama_display' => [
                        'asc' => ['entitas.nama_display' => SORT_ASC],
                        'desc' => ['entitas.nama_display' => SORT_DESC],
                        'label' => 'Pemegang Saham',
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

        if (!empty($this->pemilikNama)) {
            $value = trim($this->pemilikNama);
            $query->andWhere(['like', 'entitas.nama_display', $value]);
        }
        if (!empty($this->targetNama)) {
            $value = trim($this->targetNama);
            $query->andWhere(['like', 'perusahaan.NAMA', $value]);
        }

        $query->andFilterWhere([
            'kepemilikan_perusahaan.id' => $this->id,
            'kepemilikan_perusahaan.pemilik_entitas_id' => $this->pemilik_entitas_id,
            'kepemilikan_perusahaan.perusahaan_id' => $this->perusahaan_id,
            'kepemilikan_perusahaan.jenis_kepemilikan' => $this->jenis_kepemilikan,
            'kepemilikan_perusahaan.tanggal_data' => $this->tanggal_data,
        ]);

        return $dataProvider;
    }
}
