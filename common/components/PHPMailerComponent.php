<?php

namespace common\components;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * PHPMailer komponens Yii2 alkalmazáshoz
 * 
 * Ez a komponens integrálja a PHPMailer-t a Yii2 keretrendszerrel
 * és .env fájlból származó konfigurációkat használ.
 * 
 * @author Web Solutions Hungary Kft.
 */
class PHPMailerComponent extends Component
{
    /**
     * @var string SMTP szerver címe
     */
    public $host;
    
    /**
     * @var int SMTP port
     */
    public $port = 587;
    
    /**
     * @var string SMTP felhasználónév
     */
    public $username;
    
    /**
     * @var string SMTP jelszó
     */
    public $password;
    
    /**
     * @var string Titkosítási módszer (tls, ssl)
     */
    public $encryption = 'tls';
    
    /**
     * @var string Feladó email címe
     */
    public $fromEmail;
    
    /**
     * @var string Feladó neve
     */
    public $fromName;
    
    /**
     * @var string Karakterkódolás
     */
    public $charset = 'UTF-8';
    
    /**
     * @var bool Debug mód
     */
    public $debug = false;
    
    /**
     * @var PHPMailer PHPMailer példány
     */
    private $_mailer;

    /**
     * Komponens inicializálása
     */
    public function init()
    {
        parent::init();
        
        if (empty($this->host)) {
            throw new InvalidConfigException('SMTP host cannot be empty.');
        }
        
        if (empty($this->username)) {
            throw new InvalidConfigException('SMTP username cannot be empty.');
        }
        
        if (empty($this->password)) {
            throw new InvalidConfigException('SMTP password cannot be empty.');
        }
        
        if (empty($this->fromEmail)) {
            throw new InvalidConfigException('From email cannot be empty.');
        }
        
        $this->_mailer = new PHPMailer(true);
        $this->setupMailer();
    }
    
    /**
     * PHPMailer konfigurálása
     */
    private function setupMailer()
    {
        try {
            // SMTP beállítások
            $this->_mailer->isSMTP();
            $this->_mailer->Host = $this->host;
            $this->_mailer->SMTPAuth = true;
            $this->_mailer->Username = $this->username;
            $this->_mailer->Password = $this->password;
            $this->_mailer->Port = $this->port;
            $this->_mailer->CharSet = $this->charset;
            
            // Titkosítás beállítása
            if ($this->encryption === 'ssl') {
                $this->_mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $this->_mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            
            // Debug beállítások
            if ($this->debug) {
                $this->_mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            }
            
            // Alapértelmezett feladó beállítása
            $this->_mailer->setFrom($this->fromEmail, $this->fromName);
            
        } catch (Exception $e) {
            throw new InvalidConfigException("PHPMailer configuration error: " . $e->getMessage());
        }
    }
    
    /**
     * Email küldése
     * 
     * @param string|array $to Címzett(ek)
     * @param string $subject Tárgy
     * @param string $body Üzenet törzse
     * @param bool $isHTML HTML formátum-e
     * @param array $attachments Mellékletek
     * @param string|array $cc CC címzettek
     * @param string|array $bcc BCC címzettek
     * @return bool Sikeres küldés-e
     * @throws Exception
     */
    public function sendEmail($to, $subject, $body, $isHTML = true, $attachments = [], $cc = [], $bcc = [])
    {
        try {
            // Címzettek hozzáadása
            if (is_array($to)) {
                foreach ($to as $email => $name) {
                    if (is_numeric($email)) {
                        $this->_mailer->addAddress($name);
                    } else {
                        $this->_mailer->addAddress($email, $name);
                    }
                }
            } else {
                $this->_mailer->addAddress($to);
            }
            
            // CC címzettek
            if (!empty($cc)) {
                if (is_array($cc)) {
                    foreach ($cc as $email => $name) {
                        if (is_numeric($email)) {
                            $this->_mailer->addCC($name);
                        } else {
                            $this->_mailer->addCC($email, $name);
                        }
                    }
                } else {
                    $this->_mailer->addCC($cc);
                }
            }
            
            // BCC címzettek
            if (!empty($bcc)) {
                if (is_array($bcc)) {
                    foreach ($bcc as $email => $name) {
                        if (is_numeric($email)) {
                            $this->_mailer->addBCC($name);
                        } else {
                            $this->_mailer->addBCC($email, $name);
                        }
                    }
                } else {
                    $this->_mailer->addBCC($bcc);
                }
            }
            
            // Mellékletek
            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    if (is_array($attachment)) {
                        $this->_mailer->addAttachment($attachment['path'], $attachment['name'] ?? '');
                    } else {
                        $this->_mailer->addAttachment($attachment);
                    }
                }
            }
            
            // Email tartalma
            $this->_mailer->isHTML($isHTML);
            $this->_mailer->Subject = $subject;
            $this->_mailer->Body = $body;
            
            // Küldés
            $result = $this->_mailer->send();
            
            // Tisztítás a következő email-hez
            $this->_mailer->clearAddresses();
            $this->_mailer->clearAttachments();
            $this->_mailer->clearCCs();
            $this->_mailer->clearBCCs();
            
            return $result;
            
        } catch (Exception $e) {
            \Yii::error("Email sending failed: " . $e->getMessage(), __METHOD__);
            throw $e;
        }
    }
    
    /**
     * PHPMailer példány lekérése közvetlen használathoz
     * 
     * @return PHPMailer
     */
    public function getMailer()
    {
        return $this->_mailer;
    }
    
    /**
     * Teszt email küldése
     * 
     * @param string $to Teszt címzett
     * @return bool
     */
    public function sendTestEmail($to)
    {
        $subject = 'PHPMailer teszt email';
        $body = '<h1>Teszt email</h1><p>Ez egy teszt email a PHPMailer komponenssel.</p><p>Időpont: ' . date('Y-m-d H:i:s') . '</p>';
        
        return $this->sendEmail($to, $subject, $body, true);
    }
} 