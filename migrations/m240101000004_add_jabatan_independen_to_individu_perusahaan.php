<?php

use yii\db\Migration;

/**
 * Handles adding jabatan_ref and independen columns to table `{{%individu_perusahaan}}`.
 */
class m240101000004_add_jabatan_independen_to_individu_perusahaan extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%individu_perusahaan}}', 'jabatan_ref', 
            $this->string(50)->null()->after('JABATAN')->comment('Referensi jabatan standar: direktur_utama, direktur, komisaris_utama, komisaris, komisaris_independen, sekretaris, dll'));
        $this->addColumn('{{%individu_perusahaan}}', 'independen', 
            $this->boolean()->defaultValue(false)->after('jabatan_ref')->comment('True jika Komisaris Independen'));
        
        // Index untuk filter cepat
        $this->createIndex('{{%idx_individu_perusahaan_jabatan_ref}}', '{{%individu_perusahaan}}', 'jabatan_ref');
        $this->createIndex('{{%idx_individu_perusahaan_independen}}', '{{%individu_perusahaan}}', 'independen');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%individu_perusahaan}}', 'jabatan_ref');
        $this->dropColumn('{{%individu_perusahaan}}', 'independen');
    }
}