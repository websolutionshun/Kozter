<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
        try {
            $phpmailer = Yii::$app->phpmailer;
            
            // Email küldése PHPMailer komponenssel
            return $phpmailer->sendEmail(
                $email,                                    // Címzett
                $this->subject,                           // Tárgy
                $this->body,                             // Tartalom
                false,                                   // Text formátum (nem HTML)
                [],                                      // Mellékletek
                [],                                      // CC
                []                                       // BCC
            );
            
        } catch (\Exception $e) {
            Yii::error("Contact form email sending failed: " . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}
