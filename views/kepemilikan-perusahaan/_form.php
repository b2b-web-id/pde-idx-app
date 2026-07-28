<?php

use app\models\KepemilikanPerusahaan;
use app\models\Entitas;
use app\models\Perusahaan;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $model app\models\KepemilikanPerusahaan */

$owners = ArrayHelper::map(Entitas::getOwnerOptions(), 'id', 'nama_display');
$companies = ArrayHelper::map(Perusahaan::find()->orderBy(['NAMA' => SORT_ASC])->all(), 'ID', 'NAMA');
$form = ActiveForm::begin();
?>
<?= $form->field($model, 'pemilik_entitas_id')->dropDownList($owners, ['prompt' => 'Pilih pemegang saham']) ?>
<?= $form->field($model, 'perusahaan_id')->dropDownList($companies, ['prompt' => 'Pilih perusahaan target']) ?>
<?= $form->field($model, 'jumlah_saham')->input('number', ['min' => 0]) ?>
<?= $form->field($model, 'persentase_kepemilikan')->input('number', ['min' => 0, 'max' => 100, 'step' => '0.0001']) ?>
<?= $form->field($model, 'persentase_hak_suara')->input('number', ['min' => 0, 'max' => 100, 'step' => '0.0001']) ?>
<?= $form->field($model, 'jenis_kepemilikan')->dropDownList(KepemilikanPerusahaan::getJenisKepemilikanOptions()) ?>
<?= $form->field($model, 'status_kontrol')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'berlaku_mulai')->input('date') ?>
<?= $form->field($model, 'berlaku_sampai')->input('date') ?>
<?= $form->field($model, 'tanggal_data')->input('date') ?>
<?= $form->field($model, 'sumber_data')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'referensi_data')->textInput(['maxlength' => true]) ?>
<div class="form-group">
    <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>
