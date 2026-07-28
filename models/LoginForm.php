<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;
    
    // Rate limiting properties
    private static $maxAttempts = 5;
    private static $blockDuration = 300; // 5 minutes in seconds


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password and checks rate limiting.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            // Check rate limiting
            $loginAttempts = Yii::$app->session->get('login_attempts', 0);
            $lastAttemptTime = Yii::$app->session->get('last_login_attempt', 0);
            
            if ($loginAttempts >= self::$maxAttempts && time() - $lastAttemptTime < self::$blockDuration) {
                $remainingTime = self::$blockDuration - (time() - $lastAttemptTime);
                $this->addError($attribute, "Too many login attempts. Please try again in {$remainingTime} seconds.");
                return;
            }
            
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
                
                // Increment failed attempts
                Yii::$app->session->set('login_attempts', $loginAttempts + 1);
                Yii::$app->session->set('last_login_attempt', time());
            } else {
                // Reset attempts on successful validation
                Yii::$app->session->remove('login_attempts');
                Yii::$app->session->remove('last_login_attempt');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
    
    /**
     * Check if login is rate limited.
     * @return bool
     */
    public static function isRateLimited()
    {
        $loginAttempts = Yii::$app->session->get('login_attempts', 0);
        $lastAttemptTime = Yii::$app->session->get('last_login_attempt', 0);
        
        return $loginAttempts >= self::$maxAttempts && 
               time() - $lastAttemptTime < self::$blockDuration;
    }
    
    /**
     * Get remaining block time in seconds.
     * @return int
     */
    public static function getRemainingBlockTime()
    {
        $lastAttemptTime = Yii::$app->session->get('last_login_attempt', 0);
        return max(0, self::$blockDuration - (time() - $lastAttemptTime));
    }
}
