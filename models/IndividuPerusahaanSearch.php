<?php

namespace app\models;

use yii\data\ActiveDataProvider;

/**
 * IndividuPerusahaanSearch represents the model behind the search form of `app\models\IndividuPerusahaan`.
 */
class IndividuPerusahaanSearch extends IndividuPerusahaan
{
    public $individuNama;
    public $perusahaanNama;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'INDIVIDU_ID', 'PERUSAHAAN_ID'], 'integer'],
            [['JABATAN', 'jabatan_ref', 'STATUS', 'KETERANGAN', 'individuNama', 'perusahaanNama'], 'safe'],
            [['TANGGAL_MULAI', 'TANGGAL_AKHIR'], 'date'],
            [['independen'], 'boolean'],
        ];
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = IndividuPerusahaan::find()
            ->joinWith(['individu', 'perusahaan']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => [
                    'individu.NAMA' => [
                        'asc' => ['individu.NAMA' => SORT_ASC],
                        'desc' => ['individu.NAMA' => SORT_DESC],
                        'label' => 'Individu',
                    ],
                    'perusahaan.NAMA' => [
                        'asc' => ['perusahaan.NAMA' => SORT_ASC],
                        'desc' => ['perusahaan.NAMA' => SORT_DESC],
                        'label' => 'Perusahaan',
                    ],
                    'JABATAN',
                    'jabatan_ref',
                    'independen',
                    'STATUS',
                    'TANGGAL_MULAI',
                    'TANGGAL_AKHIR',
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Filter by LIKE on individu and perusahaan names (text search)
        if (!empty($this->individuNama)) {
            $value = trim($this->individuNama);
            $query->andWhere(['like', 'individu.NAMA', $value]);
        }
        if (!empty($this->perusahaanNama)) {
            $value = trim($this->perusahaanNama);
            $query->andWhere(['like', 'perusahaan.NAMA', $value]);
        }
        
        // Filter by exact match for JABATAN using the value from the dropdown
        $query->andFilterWhere(['JABATAN' => $this->JABATAN]);

        // Filter by exact match on other fields
        $query->andFilterWhere([
            'INDIVIDU_ID' => $this->INDIVIDU_ID,
            'PERUSAHAAN_ID' => $this->PERUSAHAAN_ID,
            'jabatan_ref' => $this->jabatan_ref,
            'independen' => $this->independen,
        ]);

        // Filter by STATUS and KETERANGAN (like search)
        $query->andFilterWhere(['like', 'STATUS', $this->STATUS])
            ->andFilterWhere(['like', 'KETERANGAN', $this->KETERANGAN]);

        return $dataProvider;
    }
}