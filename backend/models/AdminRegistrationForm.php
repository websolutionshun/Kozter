<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Admin Registration form
 */
class AdminRegistrationForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $admin_key;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Ez a felhasználónév már foglalt.'],
            ['username', 'string', 'min' => 3, 'max' => 60],
            ['username', 'validateUsername'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Ez az e-mail cím már használatban van.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength'] ?? 6],

            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'A jelszavak nem egyeznek.'],

            ['admin_key', 'required'],
            ['admin_key', 'validateAdminKey'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Felhasználónév',
            'email' => 'E-mail cím',
            'password' => 'Jelszó',
            'password_repeat' => 'Jelszó megerősítése',
            'admin_key' => 'Admin kulcs',
        ];
    }

    /**
     * Validates the username.
     * This method serves as the inline validation for username.
     * WordPress style username validation: only letters, numbers, dashes, periods, spaces and underscores.
     * But we exclude spaces for stricter validation.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateUsername($attribute, $params)
    {
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $this->username)) {
            $this->addError($attribute, 'A felhasználónév csak betűket, számokat, pontokat, kötőjeleket és aláhúzásokat tartalmazhat.');
        }

        if (preg_match('/^[._-]|[._-]$/', $this->username)) {
            $this->addError($attribute, 'A felhasználónév nem kezdődhet és nem végződhet speciális karakterrel.');
        }

        if (preg_match('/[._-]{2,}/', $this->username)) {
            $this->addError($attribute, 'A felhasználónév nem tartalmazhat egymás utáni speciális karaktereket.');
        }
    }

    /**
     * Validates the admin key.
     * This method serves as the inline validation for admin key.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateAdminKey($attribute, $params)
    {
        $expectedKey = $_ENV['ADMIN_ADD_USER_MANUAL'] ?? null;
        
        if (empty($expectedKey)) {
            $this->addError($attribute, 'Az admin regisztráció nem elérhető.');
            return;
        }

        if ($this->admin_key !== $expectedKey) {
            $this->addError($attribute, 'Hibás admin kulcs.');
        }
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful
     */
    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE; // Admin felhasználók azonnal aktívak

        return $user->save() ? $user : false;
    }
} 