<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            // Email létezés ellenőrzés eltávolítva (biztonsági okokból)
        ];
    }

    /**
     * Jelszó visszaállítási email küldése
     * Biztonsági okokból mindig sikert jelez (email enumeration védelem)
     */
    public function sendEmail()
    {
        usleep(rand(100000, 300000)); // Timing attack védelem
        
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
                if (!$user->save()) {
                    return true;
                }
            }

            try {
                $htmlContent = Yii::$app->view->renderFile(
                    Yii::getAlias('@common/mail/passwordResetToken-html.php'),
                    ['user' => $user]
                );

                Yii::$app->phpmailer->sendEmail(
                    $this->email,
                    'Jelszó visszaállítás - ' . Yii::$app->name,
                    $htmlContent,
                    true
                );
            } catch (\Exception $e) {
                // Email hiba nem befolyásolja a visszatérési értéket
            }
        }
        
        return true; // Mindig sikert jelzünk
    }
}
