<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Perusahaan */

$this->title = $model->NAMA;
$this->params['breadcrumbs'][] = ['label' => 'Perusahaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="perusahaan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'NAMA',
            'IDX_KODE',
            'ALAMAT',
            'EMAIL:email',
            'TELEPON',
            'FAKS',
            'NPWP',
            'SITUS',
            'TANGGAL_AKTA',
            'USAHA_UTAMA',
            [
                'attribute' => 'sektor_id',
                'label' => 'Sektor',
                'value' => $model->sektor ? $model->sektor->nama : ($model->SEKTOR ?: '-'),
            ],
            [
                'attribute' => 'kbli_id',
                'label' => 'KBLI',
                'value' => $model->kbli ? $model->kbli->kode . ' - ' . $model->kbli->nama : ($model->KODE_KBLI ?: '-'),
            ],
            [
                'attribute' => 'papan_pencatatan',
                'label' => 'Papan Pencatatan',
                'value' => $model->papan_pencatatan ?: '-',
            ],
            [
                'attribute' => 'tanggal_pencatatan',
                'label' => 'Tanggal Pencatatan',
                'format' => ['date', 'php:Y-m-d'],
                'value' => $model->tanggal_pencatatan ?: null,
            ],
            [
                'attribute' => 'idx_klasifikasi_id',
                'label' => 'Subsektor / Industri / Subindustri',
                'value' => $model->idxKlasifikasi ? $model->idxKlasifikasi->getFullPath() : '-',
            ],
            [
                'attribute' => 'biro_admin_efek_id',
                'label' => 'Biro Administrasi Efek',
                'value' => $model->biroAdminEfek ? $model->biroAdminEfek->kode . ' - ' . $model->biroAdminEfek->nama : '-',
            ],
            'TANGGAL_REKAM',
        ],
    ]) ?>

</div>
