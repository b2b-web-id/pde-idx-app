<?php

use yii\db\Migration;

/**
 * Adds a canonical owner entity layer for company ownership records.
 */
class m250728_000004_create_entitas_and_migrate_ownership extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%entitas}}', [
            'id' => $this->primaryKey(),
            'tipe' => $this->string(30)->notNull()->comment('individu, perusahaan, kelompok, treasury, eksternal'),
            'nama_display' => $this->string(200)->notNull(),
            'individu_id' => $this->integer()->null(),
            'perusahaan_id' => $this->integer()->null(),
            'kode_eksternal' => $this->string(50)->null(),
            'identifier_type' => $this->string(30)->null(),
            'identifier_value' => $this->string(100)->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('{{%uq_entitas_individu}}', '{{%entitas}}', 'individu_id', true);
        $this->createIndex('{{%uq_entitas_perusahaan}}', '{{%entitas}}', 'perusahaan_id', true);
        $this->createIndex('{{%idx_entitas_tipe}}', '{{%entitas}}', 'tipe');
        $this->createIndex('{{%idx_entitas_nama}}', '{{%entitas}}', 'nama_display');

        $this->addForeignKey('{{%fk_entitas_individu}}', '{{%entitas}}', 'individu_id', '{{%individu}}', 'ID', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%fk_entitas_perusahaan}}', '{{%entitas}}', 'perusahaan_id', '{{%perusahaan}}', 'ID', 'CASCADE', 'CASCADE');

        $this->db->createCommand(
            "INSERT INTO {{%entitas}} (tipe, nama_display, individu_id) " .
            "SELECT 'individu', NAMA, ID FROM {{%individu}}"
        )->execute();
        $this->db->createCommand(
            "INSERT INTO {{%entitas}} (tipe, nama_display, perusahaan_id) " .
            "SELECT 'perusahaan', NAMA, ID FROM {{%perusahaan}}"
        )->execute();

        $this->addColumn('{{%kepemilikan_perusahaan}}', 'pemilik_entitas_id', $this->integer()->null()->after('id'));
        $this->db->createCommand(
            "UPDATE {{%kepemilikan_perusahaan}} kp " .
            "INNER JOIN {{%entitas}} e ON e.perusahaan_id = kp.pemilik_id " .
            "SET kp.pemilik_entitas_id = e.id"
        )->execute();

        $unmapped = (int) $this->db->createCommand(
            "SELECT COUNT(*) FROM {{%kepemilikan_perusahaan}} WHERE pemilik_entitas_id IS NULL"
        )->queryScalar();
        if ($unmapped > 0) {
            throw new \RuntimeException('Ownership records without a matching owner entity: ' . $unmapped);
        }

        $this->dropForeignKey('{{%fk_kepemilikan_pemilik}}', '{{%kepemilikan_perusahaan}}');
        $this->dropIndex('{{%uq_kepemilikan_perusahaan_snapshot}}', '{{%kepemilikan_perusahaan}}');
        $this->dropColumn('{{%kepemilikan_perusahaan}}', 'pemilik_id');
        $this->alterColumn('{{%kepemilikan_perusahaan}}', 'pemilik_entitas_id', $this->integer()->notNull());
        $this->createIndex(
            '{{%uq_kepemilikan_perusahaan_snapshot}}',
            '{{%kepemilikan_perusahaan}}',
            ['pemilik_entitas_id', 'perusahaan_id', 'tanggal_data', 'sumber_data'],
            true
        );
        $this->createIndex('{{%idx_kepemilikan_pemilik_entitas}}', '{{%kepemilikan_perusahaan}}', 'pemilik_entitas_id');
        $this->addForeignKey('{{%fk_kepemilikan_pemilik_entitas}}', '{{%kepemilikan_perusahaan}}', 'pemilik_entitas_id', '{{%entitas}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('{{%fk_kepemilikan_pemilik_entitas}}', '{{%kepemilikan_perusahaan}}');
        $this->dropIndex('{{%idx_kepemilikan_pemilik_entitas}}', '{{%kepemilikan_perusahaan}}');
        $this->dropIndex('{{%uq_kepemilikan_perusahaan_snapshot}}', '{{%kepemilikan_perusahaan}}');
        $this->addColumn('{{%kepemilikan_perusahaan}}', 'pemilik_id', $this->integer()->null()->after('id'));
        $this->db->createCommand(
            "UPDATE {{%kepemilikan_perusahaan}} kp " .
            "INNER JOIN {{%entitas}} e ON e.id = kp.pemilik_entitas_id " .
            "SET kp.pemilik_id = e.perusahaan_id " .
            "WHERE e.tipe = 'perusahaan'"
        )->execute();
        $this->dropColumn('{{%kepemilikan_perusahaan}}', 'pemilik_entitas_id');
        $this->alterColumn('{{%kepemilikan_perusahaan}}', 'pemilik_id', $this->integer()->notNull());
        $this->createIndex(
            '{{%uq_kepemilikan_perusahaan_snapshot}}',
            '{{%kepemilikan_perusahaan}}',
            ['pemilik_id', 'perusahaan_id', 'tanggal_data', 'sumber_data'],
            true
        );
        $this->addForeignKey('{{%fk_kepemilikan_pemilik}}', '{{%kepemilikan_perusahaan}}', 'pemilik_id', '{{%perusahaan}}', 'ID', 'CASCADE', 'CASCADE');

        $this->dropForeignKey('{{%fk_entitas_perusahaan}}', '{{%entitas}}');
        $this->dropForeignKey('{{%fk_entitas_individu}}', '{{%entitas}}');
        $this->dropTable('{{%entitas}}');
    }
}
