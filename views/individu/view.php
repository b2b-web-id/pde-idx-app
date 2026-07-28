<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Individu */

$this->title = $model->NAMA;
$this->params['breadcrumbs'][] = ['label' => 'Individu', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="individu-view">

    <h1><?= Html::encode($this->title) ?></h1>

<?php
if(!Yii::$app->user->isGuest) {
?>
    <p>
      <?= Html::a('Update', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
    </p>
<?php
}
?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'NAMA',
            'ALAMAT',
            'EMAIL:email',
            'TELEPON',
            'HP',
            'FAKS',
            'SITUS',
            'TANGGAL_LAHIR',
            'TEMPAT_LAHIR',
            'TANGGAL_UPDATE',
        ],
    ]) ?>

</div>
