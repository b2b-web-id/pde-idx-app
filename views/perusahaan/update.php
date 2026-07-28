<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Perusahaan */

$this->title = 'Update Perusahaan: ' . $model->NAMA;
$this->params['breadcrumbs'][] = ['label' => 'Perusahaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->NAMA, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="perusahaan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
