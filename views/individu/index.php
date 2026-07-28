<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IndividuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Individu';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="individu-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()): ?>
    <p>
        <?= Html::a('Tambah Individu', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'NAMA',
            'ALAMAT',
            'EMAIL:email',
            'TELEPON',
            'SITUS',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'update' => Yii::$app->user->identity && Yii::$app->user->identity->isAdmin(),
                    'delete' => Yii::$app->user->identity && Yii::$app->user->identity->isAdmin(),
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
