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

    public function init()
    {
        parent::init();
    }
    
    private function initializeMailer()
    {
        if (empty($this->host) || empty($this->username) || empty($this->password) || empty($this->fromEmail)) {
            throw new InvalidConfigException('SMTP configuration incomplete.');
        }
        
        $this->_mailer = new PHPMailer(true);
        $this->setupMailer();
    }
    
    private function addRecipients($recipients, $method)
    {
        if (empty($recipients)) return;
        
        if (is_array($recipients) && !is_numeric(key($recipients))) {
            foreach ($recipients as $email => $name) {
                $this->_mailer->$method($email, $name);
            }
        } elseif (is_array($recipients)) {
            foreach ($recipients as $email) {
                $this->_mailer->$method($email);
            }
        } else {
            $this->_mailer->$method($recipients);
        }
    }
    
    private function setupMailer()
    {
        $this->_mailer->isSMTP();
        $this->_mailer->Host = $this->host;
        $this->_mailer->SMTPAuth = true;
        $this->_mailer->Username = $this->username;
        $this->_mailer->Password = $this->password;
        $this->_mailer->Port = $this->port;
        $this->_mailer->CharSet = $this->charset;
        $this->_mailer->SMTPSecure = ($this->encryption === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $this->_mailer->SMTPDebug = ($this->debug && php_sapi_name() === 'cli') ? SMTP::DEBUG_SERVER : 0;
        $this->_mailer->setFrom($this->fromEmail, $this->fromName);
    }
    
    public function sendEmail($to, $subject, $body, $isHTML = true, $attachments = [], $cc = [], $bcc = [])
    {
        if ($this->_mailer === null) {
            $this->initializeMailer();
        }
        
        $this->addRecipients($to, 'addAddress');
        $this->addRecipients($cc, 'addCC');
        $this->addRecipients($bcc, 'addBCC');
        
        foreach ($attachments as $attachment) {
            if (is_array($attachment)) {
                $this->_mailer->addAttachment($attachment['path'], $attachment['name'] ?? '');
            } else {
                $this->_mailer->addAttachment($attachment);
            }
        }
        
        $this->_mailer->isHTML($isHTML);
        $this->_mailer->Subject = $subject;
        $this->_mailer->Body = $body;
        
        $result = $this->_mailer->send();
        
        $this->_mailer->clearAddresses();
        $this->_mailer->clearAttachments();
        $this->_mailer->clearCCs();
        $this->_mailer->clearBCCs();
        
        return $result;
    }

} 