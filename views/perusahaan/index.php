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
            [
                'attribute' => 'sektor_id',
                'label' => 'Sektor',
                'value' => function ($model) {
                    return $model->sektor ? $model->sektor->nama : ($model->SEKTOR ?: '-');
                },
                'filter' => ArrayHelper::map(Sektor::find()->where(['aktif' => true])->orderBy('urutan')->all(), 'id', 'nama'),
            ],
            [
                'attribute' => 'kbli_id',
                'label' => 'KBLI',
                'value' => function ($model) {
                    return $model->kbli ? $model->kbli->kode . ' - ' . $model->kbli->nama : ($model->KODE_KBLI ?: '-');
                },
                'filter' => ArrayHelper::map(Kbli::find()->where(['aktif' => true])->orderBy('kode')->all(), 'id', function ($model) {
                    return $model->kode . ' - ' . $model->nama;
                }),
            ],
            [
                'attribute' => 'idx_klasifikasi_id',
                'label' => 'Subsektor / Industri',
                'value' => function ($model) {
                    return $model->idxKlasifikasi ? $model->idxKlasifikasi->getFullPath() : '-';
                },
                'filter' => ArrayHelper::map(
                    IdxKlasifikasi::find()->where(['level' => [3, 4], 'aktif' => true])->orderBy(['kode' => SORT_ASC])->all(),
                    'id',
                    function ($model) { return $model->getFullPath(); }
                ),
            ],
            [
                'attribute' => 'papan_pencatatan',
                'label' => 'Papan Pencatatan',
                'value' => 'papan_pencatatan',
                'filter' => ['Pengembangan' => 'Pengembangan', 'Utama' => 'Utama', 'Percepatan' => 'Percepatan'],
            ],
            [
                'attribute' => 'tanggal_pencatatan',
                'label' => 'Tgl Pencatatan',
                'format' => 'date',
            ],
            [
                'attribute' => 'biro_admin_efek_id',
                'label' => 'Biro Admin Efek',
                'value' => function ($model) {
                    return $model->biroAdminEfek ? $model->biroAdminEfek->kode . ' - ' . $model->biroAdminEfek->nama : '-';
                },
                'filter' => ArrayHelper::map(
                    BiroAdminEfek::find()->where(['aktif' => true])->orderBy('nama')->all(),
                    'id',
                    function ($model) { return $model->kode . ' - ' . $model->nama; }
                ),
            ],

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
