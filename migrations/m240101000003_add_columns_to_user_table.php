<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m240101000003_add_columns_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'role', $this->string(20)->notNull()->defaultValue('user')->after('access_token'));
        $this->addColumn('{{%user}}', 'email', $this->string(100)->null()->after('role'));
        $this->addColumn('{{%user}}', 'status', $this->smallInteger(1)->defaultValue(10)->after('email'));

        // Update existing users
        $this->update('{{%user}}', ['role' => 'admin'], ['username' => 'admin']);
        $this->update('{{%user}}', ['role' => 'user', 'status' => 10], ['username' => 'demo']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'role');
        $this->dropColumn('{{%user}}', 'email');
        $this->dropColumn('{{%user}}', 'status');
    }
}