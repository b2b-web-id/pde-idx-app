<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%individu_perusahaan}}`.
 * Relationship table between individu and perusahaan.
 */
class m240101000000_create_individu_perusahaan_table extends Migration
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

        $this->createTable('{{%individu_perusahaan}}', [
            'ID' => $this->primaryKey(),
            'INDIVIDU_ID' => $this->integer()->notNull(),
            'PERUSAHAAN_ID' => $this->integer()->notNull(),
            'JABATAN' => $this->string(100)->null(),
            'TANGGAL_MULAI' => $this->date()->null(),
            'TANGGAL_AKHIR' => $this->date()->null(),
            'STATUS' => $this->string(20)->defaultValue('aktif'),
            'KETERANGAN' => $this->string(255)->null(),
            'CREATED_AT' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'UPDATED_AT' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // Foreign keys
        $this->addForeignKey(
            '{{%fk_individu_perusahaan_individu}}',
            '{{%individu_perusahaan}}',
            'INDIVIDU_ID',
            '{{%individu}}',
            'ID',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk_individu_perusahaan_perusahaan}}',
            '{{%individu_perusahaan}}',
            'PERUSAHAAN_ID',
            '{{%perusahaan}}',
            'ID',
            'CASCADE',
            'CASCADE'
        );

        // Index for faster lookup
        $this->createIndex(
            '{{%idx_individu_perusahaan_individu}}',
            '{{%individu_perusahaan}}',
            'INDIVIDU_ID'
        );

        $this->createIndex(
            '{{%idx_individu_perusahaan_perusahaan}}',
            '{{%individu_perusahaan}}',
            'PERUSAHAAN_ID'
        );

        // Unique constraint untuk mencegah duplikasi
        $this->createIndex(
            '{{%uq_individu_perusahaan}}',
            '{{%individu_perusahaan}}',
            ['INDIVIDU_ID', 'PERUSAHAAN_ID'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%individu_perusahaan}}');
    }
}