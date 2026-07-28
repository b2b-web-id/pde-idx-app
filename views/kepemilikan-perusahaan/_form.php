<?php

use app\models\KepemilikanPerusahaan;
use app\models\Entitas;
use app\models\Perusahaan;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $model app\models\KepemilikanPerusahaan */

$ownerOptions = '';
foreach (Entitas::getOwnerOptions() as $owner) {
    $label = $owner->nama_display . ' [' . $owner->tipe . ']';
    $ownerOptions .= Html::tag('option', '', ['value' => $label, 'data-id' => $owner->id]);
}
$companyOptions = '';
foreach (Perusahaan::find()->orderBy(['NAMA' => SORT_ASC])->all() as $company) {
    $label = $company->NAMA . ($company->IDX_KODE ? ' (' . $company->IDX_KODE . ')' : ' (#' . $company->ID . ')');
    $companyOptions .= Html::tag('option', '', ['value' => $label, 'data-id' => $company->ID]);
}
$selectedOwner = $model->pemilik_entitas_id ? Entitas::findOne($model->pemilik_entitas_id) : null;
$selectedTarget = $model->perusahaan_id ? Perusahaan::findOne($model->perusahaan_id) : null;
$selectedOwnerLabel = $selectedOwner ? $selectedOwner->nama_display . ' [' . $selectedOwner->tipe . ']' : '';
$selectedTargetLabel = $selectedTarget ? $selectedTarget->NAMA . ($selectedTarget->IDX_KODE ? ' (' . $selectedTarget->IDX_KODE . ')' : ' (#' . $selectedTarget->ID . ')') : '';
$form = ActiveForm::begin();
?>
<?= $form->field($model, 'pemilik_entitas_id')->hiddenInput(['id' => 'ownership-owner-id'])->label(false) ?>
<div class="form-group">
    <label for="ownership-owner-search">Pemegang Saham</label>
    <input id="ownership-owner-search" class="form-control" type="text" list="ownership-owner-options" value="<?= Html::encode($selectedOwnerLabel) ?>" placeholder="Cari pemegang saham..." autocomplete="off">
    <datalist id="ownership-owner-options"><?= $ownerOptions ?></datalist>
</div>
<?= $form->field($model, 'perusahaan_id')->hiddenInput(['id' => 'ownership-target-id'])->label(false) ?>
<div class="form-group">
    <label for="ownership-target-search">Perusahaan Target</label>
    <input id="ownership-target-search" class="form-control" type="text" list="ownership-target-options" value="<?= Html::encode($selectedTargetLabel) ?>" placeholder="Cari perusahaan..." autocomplete="off">
    <datalist id="ownership-target-options"><?= $companyOptions ?></datalist>
</div>
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
<?php
$this->registerJs(<<<'JS'
function bindOwnershipAutocomplete(inputId, hiddenId, listId) {
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

bindOwnershipAutocomplete('ownership-owner-search', 'ownership-owner-id', 'ownership-owner-options');
bindOwnershipAutocomplete('ownership-target-search', 'ownership-target-id', 'ownership-target-options');
JS
);
?>
