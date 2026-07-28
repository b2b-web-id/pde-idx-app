<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * IndividuSearch represents the model behind the search form of `app\models\Individu`.
 */
class IndividuSearch extends Individu
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['NAMA', 'ALAMAT', 'EMAIL', 'TELEPON', 'HP', 'FAKS', 'SITUS', 'TEMPAT_LAHIR'], 'safe'],
            [['TANGGAL_LAHIR', 'TANGGAL_UPDATE'], 'date'],
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
        $query = Individu::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'NAMA' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
        ]);

        $query->andFilterWhere(['like', 'NAMA', $this->NAMA])
            ->andFilterWhere(['like', 'ALAMAT', $this->ALAMAT])
            ->andFilterWhere(['like', 'EMAIL', $this->EMAIL])
            ->andFilterWhere(['like', 'TELEPON', $this->TELEPON])
            ->andFilterWhere(['like', 'HP', $this->HP])
            ->andFilterWhere(['like', 'FAKS', $this->FAKS])
            ->andFilterWhere(['like', 'SITUS', $this->SITUS])
            ->andFilterWhere(['like', 'TEMPAT_LAHIR', $this->TEMPAT_LAHIR]);

        // Filter tanggal
        if (!empty($this->TANGGAL_LAHIR)) {
            $query->andWhere(['TANGGAL_LAHIR' => $this->TANGGAL_LAHIR]);
        }

        return $dataProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'NAMA' => 'Nama',
        ]);
    }
}