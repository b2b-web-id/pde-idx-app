<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\IndividuPerusahaan */

$this->title = 'Create Relationship';
$this->params['breadcrumbs'][] = ['label' => 'Individu - Perusahaan Relationships', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="individu-perusahaan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>