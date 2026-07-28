<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use app\models\User;

/**
 * Seed controller for initial data setup.
 */
class SeedController extends Controller
{
    /**
     * Creates default users (admin and demo).
     */
    public function actionUser()
    {
        $users = [
            ['username' => 'admin', 'password' => 'admin', 'role' => 'admin'],
            ['username' => 'demo', 'password' => 'demo', 'role' => 'user'],
        ];

        foreach ($users as $userData) {
            $existingUser = User::findByUsername($userData['username']);
            if ($existingUser === null) {
                $user = new User();
                $user->username = $userData['username'];
                $user->setPassword($userData['password']);
                $user->generateAuthKey();
                $user->role = $userData['role'];
                $user->status = 10;
                if ($user->save()) {
                    $this->stdout("User '{$userData['username']}' created successfully.\n", Console::FG_GREEN);
                } else {
                    $this->stdout("Failed to create user '{$userData['username']}'.\n", Console::FG_RED);
                    print_r($user->errors);
                }
            } else {
                $this->stdout("User '{$userData['username']}' already exists.\n", Console::FG_YELLOW);
            }
        }
    }

    /**
     * Run all seeders.
     */
    public function actionIndex()
    {
        $this->actionUser();
        $this->stdout("Seeding completed.\n", Console::FG_GREEN);
    }
}