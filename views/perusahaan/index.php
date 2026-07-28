<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Perusahaan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perusahaan-index">

    <h1><?= Html::encode($this->title) ?></h1>

<?php
if(!Yii::$app->user->isGuest) {
?>
    <p>
        <?= Html::a('Tambah Perusahaan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php
}
?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'NAMA',
            'ALAMAT',
            'TELEPON',
            'SITUS',
            'USAHA_UTAMA',
            'SEKTOR',

            Yii::$app->user->isGuest ? (
              [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
              ]
            ) : (
              [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
              ]
            ),
        ],
    ]); ?>


</div>
