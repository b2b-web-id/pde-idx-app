<?php

use yii\db\Migration;

/**
 * Creates the `user` table for application authentication.
 */
class m250728_000000_create_user_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(60)->notNull(),
            'access_token' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Seed default users (passwords: admin/admin, demo/demo)
        // Auth keys are static for dev; generate new ones in production.
        $this->insert('{{%user}}', [
            'username' => 'admin',
            'password_hash' => '$2y$12$xhSbVDCVH75F8YnaKNdRCeNxIJp2zPLR8ZgU5tsye3VwAfi80Rc9G',
            'auth_key' => 'test100key',
            'access_token' => '100-token',
        ]);
        $this->insert('{{%user}}', [
            'username' => 'demo',
            'password_hash' => '$2y$12$BZmZ/QiZBCnrI/KnUXjkAuXU8zg/otJWDTeLbNaau5s3yPFTXKWYS',
            'auth_key' => 'test101key',
            'access_token' => '101-token',
        ]);

        return true;
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
        return true;
    }
}
