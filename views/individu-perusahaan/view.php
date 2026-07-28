<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\IndividuPerusahaan;

/* @var $this yii\web\View */
/* @var $model app\models\IndividuPerusahaan */

$this->title = 'Relationship #' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Individu - Perusahaan Relationships', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="individu-perusahaan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            [
                'attribute' => 'INDIVIDU_ID',
                'value' => $model->individu ? $model->individu->NAMA : '-',
            ],
            [
                'attribute' => 'PERUSAHAAN_ID',
                'value' => $model->perusahaan ? $model->perusahaan->NAMA : '-',
            ],
            [
                'attribute' => 'JABATAN',
                'label' => 'Jabatan (Custom)',
            ],
            [
                'attribute' => 'jabatan_ref',
                'label' => 'Jabatan Referensi',
                'value' => $model->jabatan_ref ? IndividuPerusahaan::getJabatanOptions()[$model->jabatan_ref] ?? $model->jabatan_ref : '-',
            ],
            [
                'attribute' => 'independen',
                'label' => 'Komisaris Independen',
                'format' => 'boolean',
            ],
            'TANGGAL_MULAI:date',
            'TANGGAL_AKHIR:date',
            'STATUS',
            'KETERANGAN',
            'CREATED_AT:datetime',
            'UPDATED_AT:datetime',
        ],
    ]) ?>

</div>