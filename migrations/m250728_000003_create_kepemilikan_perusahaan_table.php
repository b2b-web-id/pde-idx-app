<?php

use yii\db\Migration;

/**
 * Stores company-to-company ownership snapshots.
 */
class m250728_000003_create_kepemilikan_perusahaan_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%kepemilikan_perusahaan}}', [
            'id' => $this->primaryKey(),
            'pemilik_id' => $this->integer()->notNull()->comment('Perusahaan pemegang saham'),
            'perusahaan_id' => $this->integer()->notNull()->comment('Perusahaan yang dimiliki'),
            'jumlah_saham' => $this->bigInteger()->null(),
            'persentase_kepemilikan' => $this->decimal(7, 4)->null(),
            'persentase_hak_suara' => $this->decimal(7, 4)->null(),
            'jenis_kepemilikan' => $this->string(30)->notNull()->defaultValue('langsung'),
            'status_kontrol' => $this->string(30)->null(),
            'berlaku_mulai' => $this->date()->null(),
            'berlaku_sampai' => $this->date()->null(),
            'tanggal_data' => $this->date()->notNull()->comment('Tanggal snapshot sumber'),
            'sumber_data' => $this->string(100)->notNull()->defaultValue('manual'),
            'referensi_data' => $this->string(255)->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex(
            '{{%uq_kepemilikan_perusahaan_snapshot}}',
            '{{%kepemilikan_perusahaan}}',
            ['pemilik_id', 'perusahaan_id', 'tanggal_data', 'sumber_data'],
            true
        );
        $this->createIndex('{{%idx_kepemilikan_target}}', '{{%kepemilikan_perusahaan}}', 'perusahaan_id');
        $this->createIndex('{{%idx_kepemilikan_tanggal}}', '{{%kepemilikan_perusahaan}}', 'tanggal_data');

        $this->addForeignKey(
            '{{%fk_kepemilikan_pemilik}}',
            '{{%kepemilikan_perusahaan}}',
            'pemilik_id',
            '{{%perusahaan}}',
            'ID',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk_kepemilikan_target}}',
            '{{%kepemilikan_perusahaan}}',
            'perusahaan_id',
            '{{%perusahaan}}',
            'ID',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('{{%fk_kepemilikan_target}}', '{{%kepemilikan_perusahaan}}');
        $this->dropForeignKey('{{%fk_kepemilikan_pemilik}}', '{{%kepemilikan_perusahaan}}');
        $this->dropTable('{{%kepemilikan_perusahaan}}');
    }
}
