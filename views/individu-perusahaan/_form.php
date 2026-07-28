<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Individu;
use app\models\Perusahaan;
use app\models\IndividuPerusahaan;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\IndividuPerusahaan */
/* @var $form yii\widgets\ActiveForm */

$individuList = ArrayHelper::map(Individu::find()->orderBy('NAMA')->all(), 'ID', 'NAMA');
$perusahaanList = ArrayHelper::map(Perusahaan::find()->orderBy('NAMA')->all(), 'ID', 'NAMA');
$jabatanRefList = IndividuPerusahaan::getJabatanOptions();
$statusList = IndividuPerusahaan::getStatusOptions();
?>

<div class="individu-perusahaan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'INDIVIDU_ID')->dropDownList($individuList, ['prompt' => 'Pilih Individu']) ?>

    <?= $form->field($model, 'PERUSAHAAN_ID')->dropDownList($perusahaanList, ['prompt' => 'Pilih Perusahaan']) ?>

    <?= $form->field($model, 'jabatan_ref')->dropDownList($jabatanRefList, ['prompt' => 'Pilih Jabatan Referensi']) ?>

    <?= $form->field($model, 'JABATAN')->textInput(['maxlength' => true, 'placeholder' => 'Isi manual jika jabatan tidak ada di daftar referensi']) ?>

    <?= $form->field($model, 'TANGGAL_MULAI')->input('date') ?>

    <?= $form->field($model, 'TANGGAL_AKHIR')->input('date') ?>

    <?= $form->field($model, 'STATUS')->dropDownList($statusList, ['prompt' => 'Pilih Status']) ?>

    <?= $form->field($model, 'independen')->checkbox(['label' => 'Komisaris Independen', 'labelOptions' => ['class' => 'control-label']]) ?>

    <?= $form->field($model, 'KETERANGAN')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = "
$('#individuperusahaan-jabatan_ref').on('change', function() {
    var selectedValue = $(this).val();
    var independenCheckbox = $('#individuperusahaan-independen');
    
    // Automatically check/uncheck the independen checkbox when Jabatan Referensi is 'Komisaris Independen'
    if (selectedValue === 'Komisaris Independen') {
        independenCheckbox.prop('checked', true);
    } else {
        independenCheckbox.prop('checked', false);
    }
});

// Also initialize the state on page load
$(document).ready(function() {
    var selectedValue = $('#individuperusahaan-jabatan_ref').val();
    var independenCheckbox = $('#individuperusahaan-independen');
    
    if (selectedValue === 'Komisaris Independen') {
        independenCheckbox.prop('checked', true);
    } else {
        independenCheckbox.prop('checked', false);
    }
});
";

$this->registerJs($js);
?>