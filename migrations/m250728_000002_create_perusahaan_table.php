<?php

use yii\db\Migration;

/**
 * Creates the `perusahaan` table.
 * Column names match `app\models\Perusahaan` ActiveRecord attributes (UPPERCASE).
 */
class m250728_000002_create_perusahaan_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%perusahaan}}', [
            'ID' => $this->primaryKey(),
            'NAMA' => $this->string(200)->notNull(),
            'IDX_KODE' => $this->string(4),
            'ALAMAT' => $this->string(250)->notNull(),
            'EMAIL' => $this->string(50),
            'TELEPON' => $this->string(50),
            'FAKS' => $this->string(50),
            'NPWP' => $this->string(20),
            'SITUS' => $this->string(100),
            'TANGGAL_AKTA' => $this->date(),
            'USAHA_UTAMA' => $this->string(250),
            'SEKTOR' => $this->string(250),
            'KODE_KBLI' => $this->string(5),
            'TANGGAL_REKAM' => $this->timestamp(),
        ]);

        $this->createIndex('idx-perusahaan-nama', '{{%perusahaan}}', 'NAMA', true);
        $this->createIndex('idx-perusahaan-idx-kode', '{{%perusahaan}}', 'IDX_KODE');

        return true;
    }

    public function down()
    {
        $this->dropTable('{{%perusahaan}}');
        return true;
    }
}
