<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Perusahaan */

$this->title = $model->NAMA;
$this->params['breadcrumbs'][] = ['label' => 'Perusahaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="perusahaan-view">

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
            'IDX_KODE',
            'ALAMAT',
            'EMAIL:email',
            'TELEPON',
            'FAKS',
            'NPWP',
            'SITUS',
            'TANGGAL_AKTA',
            'USAHA_UTAMA',
            'SEKTOR',
            'KODE_KBLI',
            'TANGGAL_REKAM',
        ],
    ]) ?>

</div>
