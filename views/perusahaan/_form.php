<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sektor;
use app\models\Kbli;
use app\models\IdxKlasifikasi;
use app\models\BiroAdminEfek;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Perusahaan */
/* @var $form yii\widgets\ActiveForm */

$sektorList = ArrayHelper::map(Sektor::find()->where(['aktif' => true])->orderBy('urutan')->all(), 'id', 'nama');
$kbliList = ArrayHelper::map(Kbli::find()->where(['aktif' => true])->orderBy('kode')->all(), 'id', function ($model) {
    return $model->kode . ' - ' . $model->nama;
});
$idxKlasifikasiList = IdxKlasifikasi::getGroupedDropdownList();
$biroAdminList = BiroAdminEfek::getDropdownList();
?>

<div class="perusahaan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'NAMA')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'IDX_KODE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ALAMAT')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'EMAIL')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TELEPON')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'FAKS')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'NPWP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SITUS')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TANGGAL_AKTA')->input('date') ?>

    <?= $form->field($model, 'USAHA_UTAMA')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sektor_id')->dropDownList($sektorList, ['prompt' => 'Pilih Sektor']) ?>

    <?= $form->field($model, 'kbli_id')->dropDownList($kbliList, ['prompt' => 'Pilih KBLI']) ?>

    <?= $form->field($model, 'idx_klasifikasi_id')->dropDownList($idxKlasifikasiList, ['prompt' => 'Pilih Subsektor/Industri/Subindustri']) ?>

    <?= $form->field($model, 'papan_pencatatan')->dropDownList([
        'Pengembangan' => 'Pengembangan',
        'Utama' => 'Utama',
        'Percepatan' => 'Percepatan',
    ], ['prompt' => 'Pilih Papan Pencatatan']) ?>

    <?= $form->field($model, 'tanggal_pencatatan')->input('date') ?>

    <?= $form->field($model, 'biro_admin_efek_id')->dropDownList($biroAdminList, ['prompt' => 'Pilih Biro Admin Efek']) ?>

    <?= $form->field($model, 'KODE_KBLI')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
