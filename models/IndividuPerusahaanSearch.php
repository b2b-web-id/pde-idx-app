<?php

namespace app\models;

use yii\data\ActiveDataProvider;

/**
 * IndividuPerusahaanSearch represents the model behind the search form of `app\models\IndividuPerusahaan`.
 */
class IndividuPerusahaanSearch extends IndividuPerusahaan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'INDIVIDU_ID', 'PERUSAHAAN_ID'], 'integer'],
            [['JABATAN', 'jabatan_ref', 'STATUS', 'KETERANGAN'], 'safe'], // JABATAN is now expected to be an exact string from dropdown
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
            'sort' => [ // Defined sort attributes for joined tables
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

        // Filter by exact match on INDIVIDU_ID and PERUSAHAAN_ID (assuming dropdowns provide IDs)
        $query->andFilterWhere([
            'INDIVIDU_ID' => $this->INDIVIDU_ID,
            'PERUSAHAAN_ID' => $this->PERUSAHAAN_ID,
            'jabatan_ref' => $this->jabatan_ref,
            'independen' => $this->independen,
        ]);
        
        // Filter by exact match for JABATAN using the value from the dropdown
        if ($this->JABATAN !== null) {
            $query->andWhere(['JABATAN' => $this->JABATAN]);
        }

        // Filter by STATUS and KETERANGAN (like search)
        $query->andFilterWhere(['like', 'STATUS', $this->STATUS])
            ->andFilterWhere(['like', 'KETERANGAN', $this->KETERANGAN]);
            
        // Filtering by names in GridView with attribute 'individu.NAMA' and 'perusahaan.NAMA'
        // requires joining and filtering by the respective table's ID, or by name if needed.
        // Since the search model attributes are INDIVIDU_ID and PERUSAHAAN_ID (integers for dropdowns),
        // we should filter using those IDs via the joined tables.
        if ($this->INDIVIDU_ID) {
            $query->andWhere(['individu.ID' => $this->INDIVIDU_ID]);
        }
        if ($this->PERUSAHAAN_ID) {
            $query->andWhere(['perusahaan.ID' => $this->PERUSAHAAN_ID]);
        }

        return $dataProvider;
    }
}