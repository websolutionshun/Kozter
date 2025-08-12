<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Elfelejtett jelszó form
 */
class ForgotPasswordForm extends Model
{
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'required', 'message' => 'Az e-mail cím megadása kötelező.'],
            ['email', 'email', 'message' => 'Érvényes e-mail címet adjon meg.'],
            ['email', 'exist',
                'targetClass' => User::class,
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Nincs regisztrált felhasználó ezzel az e-mail címmel.'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => 'E-mail cím',
        ];
    }

    /**
     * Jelszó visszaállítási email küldése
     *
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName'] ?? Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Jelszó visszaállítás - ' . Yii::$app->name)
            ->send();
    }
} 