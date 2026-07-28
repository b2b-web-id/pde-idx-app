<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%login_attempts}}`.
 * Tracks failed login attempts per IP address.
 */
class m240101000002_create_login_attempts_table extends Migration
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

        $this->createTable('{{%login_attempts}}', [
            'id' => $this->primaryKey(),
            'ip_address' => $this->string(45)->notNull(),
            'username' => $this->string(50)->null(),
            'attempt_time' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // Index for faster lookup and cleanup
        $this->createIndex('{{%idx_login_attempts_ip}}', '{{%login_attempts}}', 'ip_address');
        $this->createIndex('{{%idx_login_attempts_time}}', '{{%login_attempts}}', 'attempt_time');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%login_attempts}}');
    }
}