<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Felhasználónév',
            'email' => 'E-mail cím',
            'password' => 'Jelszó',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => '{attribute} megadása kötelező.'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Ez a felhasználónév már foglalt.'],
            ['username', 'string', 'min' => 2, 'max' => 255, 'tooShort' => '{attribute} túl rövid (minimum {min} karakter).', 'tooLong' => '{attribute} túl hosszú (maximum {max} karakter).'],

            ['email', 'trim'],
            ['email', 'required', 'message' => '{attribute} megadása kötelező.'],
            ['email', 'email', 'message' => 'Érvényes e-mail címet adj meg.'],
            ['email', 'string', 'max' => 255, 'tooLong' => '{attribute} túl hosszú (maximum {max} karakter).'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Ez az e-mail cím már használatban van.'],

            ['password', 'required', 'message' => '{attribute} megadása kötelező.'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength'], 'tooShort' => '{attribute} túl rövid (minimum {min} karakter).'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        return $user->save() && $this->sendEmail($user);
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
