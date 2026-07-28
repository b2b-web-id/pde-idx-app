<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Individu;
use app\models\Perusahaan;
use app\models\IndividuPerusahaan;

/* @var $this yii\web\View */
/* @var $model app\models\IndividuPerusahaan */
/* @var $form yii\widgets\ActiveForm */

$individuOptions = '';
foreach (Individu::find()->orderBy('NAMA')->all() as $individu) {
    $label = $individu->NAMA . ' (#' . $individu->ID . ')';
    $individuOptions .= Html::tag('option', '', ['value' => $label, 'data-id' => $individu->ID]);
}
$perusahaanOptions = '';
foreach (Perusahaan::find()->orderBy('NAMA')->all() as $perusahaan) {
    $label = $perusahaan->NAMA . ($perusahaan->IDX_KODE ? ' (' . $perusahaan->IDX_KODE . ')' : ' (#' . $perusahaan->ID . ')');
    $perusahaanOptions .= Html::tag('option', '', ['value' => $label, 'data-id' => $perusahaan->ID]);
}
$selectedIndividu = $model->INDIVIDU_ID ? Individu::findOne($model->INDIVIDU_ID) : null;
$selectedPerusahaan = $model->PERUSAHAAN_ID ? Perusahaan::findOne($model->PERUSAHAAN_ID) : null;
$selectedIndividuLabel = $selectedIndividu ? $selectedIndividu->NAMA . ' (#' . $selectedIndividu->ID . ')' : '';
$selectedPerusahaanLabel = $selectedPerusahaan ? $selectedPerusahaan->NAMA . ($selectedPerusahaan->IDX_KODE ? ' (' . $selectedPerusahaan->IDX_KODE . ')' : ' (#' . $selectedPerusahaan->ID . ')') : '';
$jabatanRefList = IndividuPerusahaan::getJabatanOptions();
$statusList = IndividuPerusahaan::getStatusOptions();
?>

<div class="individu-perusahaan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'INDIVIDU_ID')->hiddenInput(['id' => 'individu-perusahaan-individu-id'])->label(false) ?>
    <div class="form-group">
        <label for="individu-perusahaan-individu-search">Individu</label>
        <input id="individu-perusahaan-individu-search" class="form-control" type="text" list="individu-options" value="<?= Html::encode($selectedIndividuLabel) ?>" placeholder="Cari individu..." autocomplete="off">
        <datalist id="individu-options"><?= $individuOptions ?></datalist>
    </div>

    <?= $form->field($model, 'PERUSAHAAN_ID')->hiddenInput(['id' => 'individu-perusahaan-perusahaan-id'])->label(false) ?>
    <div class="form-group">
        <label for="individu-perusahaan-perusahaan-search">Perusahaan</label>
        <input id="individu-perusahaan-perusahaan-search" class="form-control" type="text" list="perusahaan-options" value="<?= Html::encode($selectedPerusahaanLabel) ?>" placeholder="Cari perusahaan..." autocomplete="off">
        <datalist id="perusahaan-options"><?= $perusahaanOptions ?></datalist>
    </div>

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
function bindAutocomplete(inputId, hiddenId, listId) {
    var input = document.getElementById(inputId);
    var hidden = document.getElementById(hiddenId);
    var options = document.querySelectorAll('#' + listId + ' option');

    function syncValue() {
        hidden.value = '';
        Array.prototype.some.call(options, function(option) {
            if (option.value === input.value) {
                hidden.value = option.getAttribute('data-id');
                return true;
            }
            return false;
        });
        input.classList.toggle('is-invalid', input.value !== '' && hidden.value === '');
    }

    input.addEventListener('input', syncValue);
    input.addEventListener('change', syncValue);
    syncValue();
}

bindAutocomplete('individu-perusahaan-individu-search', 'individu-perusahaan-individu-id', 'individu-options');
bindAutocomplete('individu-perusahaan-perusahaan-search', 'individu-perusahaan-perusahaan-id', 'perusahaan-options');

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
