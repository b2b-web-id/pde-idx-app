<?php

use yii\helpers\Html;

$this->title = 'Tambah Snapshot Kepemilikan';
$this->params['breadcrumbs'][] = ['label' => 'Kepemilikan Perusahaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_form', ['model' => $model]) ?>
