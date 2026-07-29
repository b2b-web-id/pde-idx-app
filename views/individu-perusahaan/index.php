<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Individu;
use app\models\Perusahaan;
use app\models\IndividuPerusahaan;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IndividuPerusahaanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Individu - Perusahaan Relationships';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="individu-perusahaan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Relationship', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'individuNama',
                'label' => 'Individu',
                'value' => function ($model) {
                    return $model->individu ? $model->individu->NAMA : '-';
                },
                'filter' => Html::activeTextInput($searchModel, 'individuNama', ['class' => 'form-control', 'placeholder' => 'Cari individu...']),
            ],
            [
                'attribute' => 'perusahaanNama',
                'label' => 'Perusahaan',
                'value' => function ($model) {
                    return $model->perusahaan ? $model->perusahaan->NAMA : '-';
                },
                'filter' => Html::activeTextInput($searchModel, 'perusahaanNama', ['class' => 'form-control', 'placeholder' => 'Cari perusahaan...']),
            ],
            [
                'attribute' => 'JABATAN',
                'label' => 'Jabatan (Custom)',
                'value' => 'JABATAN',
                'filter' => [
                    'KETUA KOMITE AUDIT' => 'KETUA KOMITE AUDIT',
                    'ANGGOTA KOMITE AUDIT' => 'ANGGOTA KOMITE AUDIT',
                    'KOMISARIS UTAMA' => 'KOMISARIS UTAMA',
                    'KOMISARIS' => 'KOMISARIS',
                    'DIREKTUR UTAMA' => 'DIREKTUR UTAMA',
                    'WAKIL DIREKTUR UTAMA' => 'WAKIL DIREKTUR UTAMA',
                    'DIREKTUR' => 'DIREKTUR',
                    'SEKRETARIS PERUSAHAAN' => 'SEKRETARIS PERUSAHAAN',
                    'Lainnya' => 'Lainnya',
                ],
            ],
            [
                'attribute' => 'jabatan_ref',
                'label' => 'Jabatan Ref',
                'value' => function ($model) {
                    return $model->jabatan_ref ? $model->getJabatanRefLabel($model->jabatan_ref) : '-';
                },
                'filter' => IndividuPerusahaan::getJabatanOptions(),
            ],
            [
                'attribute' => 'independen',
                'label' => 'Independen',
                'format' => 'boolean',
                'filter' => [0 => 'Tidak', 1 => 'Ya'],
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>