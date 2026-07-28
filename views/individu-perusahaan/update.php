<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\IndividuPerusahaan */

$this->title = 'Update Relationship: ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Individu - Perusahaan Relationships', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Relationship #' . $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="individu-perusahaan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>