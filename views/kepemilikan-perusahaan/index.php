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
                'attribute' => 'pemilikNama',
                'label' => 'Pemegang Saham',
                'value' => function ($model) { return $model->pemilikEntitas ? $model->pemilikEntitas->nama_display : '-'; },
                'filter' => Html::activeTextInput($searchModel, 'pemilikNama', ['class' => 'form-control', 'placeholder' => 'Cari pemegang saham...']),
            ],
            [
                'attribute' => 'targetNama',
                'label' => 'Perusahaan Target',
                'value' => function ($model) { return $model->perusahaan ? $model->perusahaan->NAMA : '-'; },
                'filter' => Html::activeTextInput($searchModel, 'targetNama', ['class' => 'form-control', 'placeholder' => 'Cari perusahaan target...']),
            ],
            'persentase_kepemilikan:decimal',
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
