<?php

namespace app\models;

use yii\data\ActiveDataProvider;

/**
 * PerusahaanSearch represents the model behind the search form of `app\models\Perusahaan`.
 */
class PerusahaanSearch extends Perusahaan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'sektor_id', 'kbli_id', 'idx_klasifikasi_id', 'biro_admin_efek_id'], 'integer'],
            [['NAMA', 'IDX_KODE', 'ALAMAT', 'EMAIL', 'TELEPON', 'FAKS', 'NPWP', 'SITUS', 'USAHA_UTAMA', 'SEKTOR', 'KODE_KBLI', 'papan_pencatatan'], 'safe'],
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
        // Use explicit table alias to avoid ambiguous column names
        $query = Perusahaan::find()
            ->joinWith(['sektor s' => function ($q) {
                $q->from(['s' => 'sektor']);
            }])
            ->joinWith(['kbli k' => function ($q) {
                $q->from(['k' => 'kbli']);
            }])
            ->joinWith(['idxKlasifikasi ik' => function ($q) {
                $q->from(['ik' => 'idx_klasifikasi']);
            }])
            ->joinWith(['biroAdminEfek bae' => function ($q) {
                $q->from(['bae' => 'biro_admin_efek']);
            }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'NAMA' => SORT_ASC,
                ],
                'attributes' => [
                    'NAMA',
                    'ALAMAT',
                    'TELEPON',
                    'SITUS',
                    'USAHA_UTAMA',
                    'SEKTOR',
                    'KODE_KBLI',
                    'sektor_nama' => [
                        'asc' => ['s.nama' => SORT_ASC],
                        'desc' => ['s.nama' => SORT_DESC],
                        'label' => 'Sektor',
                    ],
                    'kbli_kode' => [
                        'asc' => ['k.kode' => SORT_ASC],
                        'desc' => ['k.kode' => SORT_DESC],
                        'label' => 'KBLI',
                    ],
                    'idx_klasifikasi_nama' => [
                        'asc' => ['ik.nama' => SORT_ASC],
                        'desc' => ['ik.nama' => SORT_DESC],
                        'label' => 'Subsektor/Industri',
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Use explicit table prefix for ambiguous columns
        $query->andFilterWhere([
            'perusahaan.ID' => $this->ID,
            'perusahaan.IDX_KODE' => $this->IDX_KODE,
            'perusahaan.sektor_id' => $this->sektor_id,
            'perusahaan.kbli_id' => $this->kbli_id,
            'perusahaan.idx_klasifikasi_id' => $this->idx_klasifikasi_id,
            'perusahaan.biro_admin_efek_id' => $this->biro_admin_efek_id,
        ]);

        $query->andFilterWhere(['like', 'perusahaan.NAMA', $this->NAMA])
            ->andFilterWhere(['like', 'perusahaan.ALAMAT', $this->ALAMAT])
            ->andFilterWhere(['like', 'perusahaan.EMAIL', $this->EMAIL])
            ->andFilterWhere(['like', 'perusahaan.TELEPON', $this->TELEPON])
            ->andFilterWhere(['like', 'perusahaan.FAKS', $this->FAKS])
            ->andFilterWhere(['like', 'perusahaan.NPWP', $this->NPWP])
            ->andFilterWhere(['like', 'perusahaan.SITUS', $this->SITUS])
            ->andFilterWhere(['like', 'perusahaan.USAHA_UTAMA', $this->USAHA_UTAMA])
            ->andFilterWhere(['like', 'perusahaan.SEKTOR', $this->SEKTOR])
            ->andFilterWhere(['like', 'perusahaan.KODE_KBLI', $this->KODE_KBLI])
            ->andFilterWhere(['like', 'perusahaan.papan_pencatatan', $this->papan_pencatatan]);

        return $dataProvider;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'perusahaan';
    }
}