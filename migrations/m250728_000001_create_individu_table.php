<?php

use yii\db\Migration;

/**
 * Creates the `individu` table.
 * Column names match `app\models\Individu` ActiveRecord attributes (UPPERCASE).
 */
class m250728_000001_create_individu_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%individu}}', [
            'ID' => $this->primaryKey(),
            'NAMA' => $this->string(200)->notNull(),
            'ALAMAT' => $this->string(250),
            'EMAIL' => $this->string(100),
            'TELEPON' => $this->string(50),
            'HP' => $this->string(50),
            'FAKS' => $this->string(50),
            'SITUS' => $this->string(100),
            'TANGGAL_LAHIR' => $this->date(),
            'TEMPAT_LAHIR' => $this->string(100),
            'TANGGAL_UPDATE' => $this->timestamp()->null(),
        ]);

        $this->createIndex('idx-individu-nama', '{{%individu}}', 'NAMA', true);

        return true;
    }

    public function down()
    {
        $this->dropTable('{{%individu}}');
        return true;
    }
}
