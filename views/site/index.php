<?php
/* @var $this yii\web\View */
$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div class="jumbotron">
        <h1><?= Yii::$app->name ?></h1>
        <p class="lead">Basis Data Pelaku Usaha Berdasarkan Data IDX</p>
    </div>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <h2>Perusahaan</h2>
                <p>Data perusahaan yang terdapat dalam IDX baik itu pemegang saham
                   anak perusahaan maupun institusi pencatat.</p>
                <p><a class="btn btn-default" href="perusahaan/">Klik</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Individu</h2>
                <p>Data individu perorangan yang terdapat dalam IDX baik itu
                   pemegang saham, pengurus termasuk sekertaris perusahaan maupun
                   tim audit.</p>
                <p><a class="btn btn-default" href="individu/">Klik</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Hubungan</h2>
                <p>Hubungan antara Individu dan Perusahaan (jabatan, periode, status).</p>
                <p><a class="btn btn-default" href="individu-perusahaan/">Klik</a></p>
            </div>
        </div>
    </div>
</div>
