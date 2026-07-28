<?php

use yii\db\Migration;

/**
 * Handles adding IDX-specific fields to perusahaan table
 * and creating subsektor/industri hierarchy.
 */
class m240101000006_add_idx_fields_to_perusahaan extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // 1. Create Subsektor/Industri/Subindustri hierarchy table (self-referencing)
        $this->createTable('{{%idx_klasifikasi}}', [
            'id' => $this->primaryKey(),
            'kode' => $this->string(10)->notNull()->unique(),
            'nama' => $this->string(150)->notNull(),
            'level' => $this->tinyInteger(1)->notNull()->defaultValue(1)
                ->comment('1=Sektor, 2=Subsektor, 3=Industri, 4=Subindustri'),
            'parent_id' => $this->integer()->null()->comment('FK ke idx_klasifikasi (hierarchy)'),
            'sektor_id' => $this->integer()->null()->comment('FK ke sektor (root)'),
            'deskripsi' => $this->text()->null(),
            'urutan' => $this->integer()->defaultValue(0),
            'aktif' => $this->boolean()->defaultValue(true),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('{{%idx_idx_klasifikasi_parent}}', '{{%idx_klasifikasi}}', 'parent_id');
        $this->createIndex('{{%idx_idx_klasifikasi_level}}', '{{%idx_klasifikasi}}', 'level');
        $this->createIndex('{{%idx_idx_klasifikasi_sektor}}', '{{%idx_klasifikasi}}', 'sektor_id');
        $this->createIndex('{{%idx_idx_klasifikasi_aktif}}', '{{%idx_klasifikasi}}', 'aktif');

        $this->addForeignKey(
            '{{%fk_idx_klasifikasi_parent}}',
            '{{%idx_klasifikasi}}',
            'parent_id',
            '{{%idx_klasifikasi}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk_idx_klasifikasi_sektor}}',
            '{{%idx_klasifikasi}}',
            'sektor_id',
            '{{%sektor}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        // 2. Biro Administrasi Efek table
        $this->createTable('{{%biro_admin_efek}}', [
            'id' => $this->primaryKey(),
            'kode' => $this->string(10)->notNull()->unique(),
            'nama' => $this->string(150)->notNull(),
            'alamat' => $this->text()->null(),
            'telepon' => $this->string(50)->null(),
            'email' => $this->string(100)->null(),
            'aktif' => $this->boolean()->defaultValue(true),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('{{%idx_biro_admin_efek_kode}}', '{{%biro_admin_efek}}', 'kode');
        $this->createIndex('{{%idx_biro_admin_efek_aktif}}', '{{%biro_admin_efek}}', 'aktif');

        // 3. Add columns to Perusahaan
        $this->addColumn('{{%perusahaan}}', 'papan_pencatatan', 
            $this->string(20)->null()->after('KODE_KBLI')->comment('Pengembangan, Utama, Percepatan'));
        $this->addColumn('{{%perusahaan}}', 'tanggal_pencatatan', 
            $this->date()->null()->after('papan_pencatatan')->comment('Tanggal pencatatan di IDX'));
        $this->addColumn('{{%perusahaan}}', 'idx_klasifikasi_id', 
            $this->integer()->null()->after('tanggal_pencatatan')->comment('FK ke idx_klasifikasi (Subsektor/Industri/Subindustri)'));
        $this->addColumn('{{%perusahaan}}', 'biro_admin_efek_id', 
            $this->integer()->null()->after('idx_klasifikasi_id')->comment('FK ke biro_admin_efek'));

        $this->createIndex('{{%idx_perusahaan_papan}}', '{{%perusahaan}}', 'papan_pencatatan');
        $this->createIndex('{{%idx_perusahaan_idx_klasifikasi}}', '{{%perusahaan}}', 'idx_klasifikasi_id');
        $this->createIndex('{{%idx_perusahaan_biro_admin}}', '{{%perusahaan}}', 'biro_admin_efek_id');

        $this->addForeignKey(
            '{{%fk_perusahaan_idx_klasifikasi}}',
            '{{%perusahaan}}',
            'idx_klasifikasi_id',
            '{{%idx_klasifikasi}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk_perusahaan_biro_admin_efek}}',
            '{{%perusahaan}}',
            'biro_admin_efek_id',
            '{{%biro_admin_efek}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        // 4. Insert sample Biro Admin Efek
        $this->batchInsert('{{%biro_admin_efek}}', ['kode', 'nama', 'alamat', 'telepon', 'email', 'aktif'], [
            ['DATINDO', 'PT Datindo Entrycom', 'Jl. Hayam Wuruk No. 28, Jakarta', '021-3508077', 'info@datindo.com', true],
            ['EDI', 'PT Edi Indonesia', 'Jl. Sudirman Kav. 52-53, Jakarta', '021-5153003', 'info@edi.co.id', true],
            ['KSEI', 'PT Kustodian Sentral Efek Indonesia (KSEI)', 'Gd. Bursa Efek Indonesia Tower 1, Jakarta', '021-5152855', 'info@ksei.co.id', true],
            ['SEI', 'PT Sarana Elektronik Indonesia', 'Jl. Jend. Sudirman Kav. 52-53, Jakarta', '021-5153003', 'info@sei.co.id', true],
        ]);

        // 5. Insert sample IDX Klasifikasi hierarchy for Teknologi sector
        // First get sektor Teknologi ID
        // We'll use raw SQL for this since we need the ID
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk_perusahaan_biro_admin_efek}}', '{{%perusahaan}}');
        $this->dropForeignKey('{{%fk_perusahaan_idx_klasifikasi}}', '{{%perusahaan}}');
        $this->dropColumn('{{%perusahaan}}', 'biro_admin_efek_id');
        $this->dropColumn('{{%perusahaan}}', 'idx_klasifikasi_id');
        $this->dropColumn('{{%perusahaan}}', 'tanggal_pencatatan');
        $this->dropColumn('{{%perusahaan}}', 'papan_pencatatan');

        $this->dropForeignKey('{{%fk_idx_klasifikasi_sektor}}', '{{%idx_klasifikasi}}');
        $this->dropForeignKey('{{%fk_idx_klasifikasi_parent}}', '{{%idx_klasifikasi}}');
        $this->dropTable('{{%idx_klasifikasi}}');

        $this->dropTable('{{%biro_admin_efek}}');
    }
}