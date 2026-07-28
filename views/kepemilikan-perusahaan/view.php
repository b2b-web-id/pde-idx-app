<?php

use app\models\KepemilikanPerusahaan;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Snapshot Kepemilikan: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kepemilikan Perusahaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()): ?>
<p>
    <?= Html::a('Ubah', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Hapus', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => ['confirm' => 'Hapus snapshot ini?', 'method' => 'post'],
    ]) ?>
</p>
<?php endif; ?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        ['label' => 'Pemegang Saham', 'value' => $model->pemilikEntitas ? $model->pemilikEntitas->nama_display : '-'],
        ['label' => 'Perusahaan Target', 'value' => $model->perusahaan ? $model->perusahaan->NAMA : '-'],
        'jumlah_saham',
        'persentase_kepemilikan:decimal',
        'persentase_hak_suara:decimal',
        ['attribute' => 'jenis_kepemilikan', 'value' => KepemilikanPerusahaan::getJenisKepemilikanOptions()[$model->jenis_kepemilikan] ?? $model->jenis_kepemilikan],
        'status_kontrol',
        'berlaku_mulai:date',
        'berlaku_sampai:date',
        'tanggal_data:date',
        'sumber_data',
        'referensi_data',
    ],
]) ?>
