<?php

use app\models\KepemilikanPerusahaan;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KepemilikanPerusahaanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kepemilikan Perusahaan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kepemilikan-perusahaan-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()): ?>
        <p><?= Html::a('Tambah Snapshot', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'pemilik.NAMA',
                'label' => 'Perusahaan Pemilik',
                'value' => function ($model) { return $model->pemilik ? $model->pemilik->NAMA : '-'; },
            ],
            [
                'attribute' => 'perusahaan.NAMA',
                'label' => 'Perusahaan Target',
                'value' => function ($model) { return $model->perusahaan ? $model->perusahaan->NAMA : '-'; },
            ],
            'persentase_kepemilikan:decimal',
            'persentase_hak_suara:decimal',
            [
                'attribute' => 'jenis_kepemilikan',
                'filter' => KepemilikanPerusahaan::getJenisKepemilikanOptions(),
            ],
            'tanggal_data:date',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]) ?>
    <?php Pjax::end(); ?>
</div>
