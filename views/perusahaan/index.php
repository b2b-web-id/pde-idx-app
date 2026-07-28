<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Sektor;
use app\models\Kbli;
use app\models\IdxKlasifikasi;
use app\models\BiroAdminEfek;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PerusahaanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Perusahaan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perusahaan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()): ?>
    <p>
        <?= Html::a('Tambah Perusahaan', ['create'], ['class' => 'btn btn-success']) ?>
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
            'TELEPON',
            'SITUS',
            'USAHA_UTAMA',
            ['class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => Yii::$app->user->identity && Yii::$app->user->identity->isAdmin(),
                    'delete' => Yii::$app->user->identity && Yii::$app->user->identity->isAdmin(),
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
