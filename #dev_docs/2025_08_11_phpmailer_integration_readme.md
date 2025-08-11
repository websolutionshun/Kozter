# PHPMailer Clean Integráció Dokumentáció

**Fejlesztő:** Web Solutions Hungary Kft.  
**Dátum:** 2025. 08. 11.  
**Verzió:** 2.0 - Clean Architecture

## Áttekintés

Ez a dokumentáció leírja a PHPMailer/PHPMailer csomag **clean integrációját** a Yii2 Advanced keretrendszerbe. Az integráció .env fájlból származó konfigurációkat használ, és a `mailer` komponens teljesen el lett távolítva a tiszta architektúra érdekében.

**Fontos:** Ez egy **clean PHPMailer integráció**! A `mailer` komponens teljesen el lett távolítva, csak a `phpmailer` komponens van jelen. Ez garantálja, hogy minden email küldés PHPMailer-rel történik.

## Email Komponens

### PHPMailer (`Yii::$app->phpmailer`) - Egyetlen Email Motor
- **Közvetlen PHPMailer API** - teljes funkcionalitás és kontroll
- **Clean architektúra** - nincs wrapper vagy abstrakció
- **Teljes PHPMailer feature set** - minden funkció elérhető
- **Egyértelmű használat** - csak egy módja van az email küldésnek

## Telepített Csomag

- **phpmailer/phpmailer**: ^6.10
- Telepítve: `composer require phpmailer/phpmailer`

## Fájlstruktúra

### Új fájlok:
- `common/components/PHPMailerComponent.php` - PHPMailer Yii2 komponens
- `.env` - Környezeti változók (felhasználó által létrehozandó)

### Módosított fájlok:
- `common/config/main.php` - PHPMailer komponens regisztráció
- `environments/dev/common/config/main-local.php` - Development SMTP konfiguráció (mailer komponens eltávolítva)
- `environments/prod/common/config/main-local.php` - Production SMTP konfiguráció (mailer komponens eltávolítva)
- `frontend/models/ContactForm.php` - PHPMailer használatra váltva
- `backend/config/bootstrap.php` - DotEnv betöltés
- `frontend/config/bootstrap.php` - DotEnv betöltés
- `console/config/bootstrap.php` - DotEnv betöltés

## Konfigurációs Változók (.env)

A következő változókat kell beállítani a .env fájlban (mindkét email komponens használja):

```env
# Adatbázis beállítások
DB_HOST=localhost
DB_NAME=yii2advanced
DB_USERNAME=root
DB_PASSWORD=

# SMTP beállítások (Symfony Mailer + PHPMailer)
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=your-app-password
SMTP_ENCRYPTION=tls
SMTP_FROM_EMAIL=your-email@gmail.com
SMTP_FROM_NAME=Your Name
SMTP_CHARSET=UTF-8

# Email transport beállítások (már nincs rá szükség)
# MAIL_USE_FILE_TRANSPORT=false  # Törölhető
```

### Változó magyarázatok:

**Adatbázis:**
- `DB_HOST`: Adatbázis szerver címe
- `DB_NAME`: Adatbázis neve
- `DB_USERNAME`: Adatbázis felhasználónév
- `DB_PASSWORD`: Adatbázis jelszó

**SMTP (mindkét komponens):**
- `SMTP_HOST`: SMTP szerver címe
- `SMTP_PORT`: SMTP port (587 TLS-hez, 465 SSL-hez)
- `SMTP_USERNAME`: SMTP felhasználónév
- `SMTP_PASSWORD`: SMTP jelszó vagy app-specific jelszó
- `SMTP_ENCRYPTION`: Titkosítás típusa (tls, ssl)
- `SMTP_FROM_EMAIL`: Alapértelmezett feladó email cím
- `SMTP_FROM_NAME`: Alapértelmezett feladó név
- `SMTP_CHARSET`: Karakterkódolás (csak PHPMailer)

**Email transport:**
- `MAIL_USE_FILE_TRANSPORT`: ~~törölt~~ (már nincs rá szükség, a PHPMailer mindig valódi küldést végez)

## Email Küldés - Egyetlen Módszer

### PHPMailer (`Yii::$app->phpmailer`) - Egyetlen Email API

**Alapvető használat:**
```php
<?php
// PHPMailer - egyetlen módja az email küldésnek
$phpmailer = Yii::$app->phpmailer;

// Egyszerű email küldés
$result = $phpmailer->sendEmail(
    'test@example.com',         // Címzett
    'Teszt Email',              // Tárgy
    'Egyszerű szöveges email',  // Tartalom
    false                       // Text formátum (nem HTML)
);

// HTML email küldés
$result = $phpmailer->sendEmail(
    'test@example.com',
    'HTML Email',
    '<h1>HTML email</h1><p>Rich content email.</p>',
    true                        // HTML formátum
);

// Email mellékletekkel
$result = $phpmailer->sendEmail(
    'test@example.com',
    'Email melléklettel',
    '<h1>Dokumentum csatolva</h1>',
    true,
    ['/path/to/attachment.pdf'] // Mellékletek
);
```

**ContactForm példa (frontend/models/ContactForm.php):**
```php
<?php
public function sendEmail($email)
{
    try {
        $phpmailer = Yii::$app->phpmailer;
        
        return $phpmailer->sendEmail(
            $email,           // Címzett
            $this->subject,   // Tárgy
            $this->body,      // Tartalom
            false,            // Text formátum
            [],               // Mellékletek
            [],               // CC
            []                // BCC
        );
        
    } catch (\Exception $e) {
        Yii::error("Contact form email failed: " . $e->getMessage());
        return false;
    }
}
```

## Használat

### PHPMailer - Alapvető Email Küldés

```php
<?php
// Kontrollerben vagy modelben
$phpmailer = Yii::$app->phpmailer;

try {
    $result = $phpmailer->sendEmail(
        'recipient@example.com',    // Címzett
        'Teszt Email',              // Tárgy
        '<h1>Szia!</h1><p>Ez egy teszt email.</p>', // HTML tartalom
        true,                       // HTML formátum
        [],                         // Mellékletek (opcionális)
        [],                         // CC címzettek (opcionális)
        []                          // BCC címzettek (opcionális)
    );
    
    if ($result) {
        echo "Email sikeresen elküldve!";
    }
} catch (Exception $e) {
    echo "Hiba: " . $e->getMessage();
}
```

### Többszörös Címzettek

```php
<?php
$recipients = [
    'user1@example.com' => 'User One',
    'user2@example.com' => 'User Two',
    'user3@example.com'  // Név nélkül
];

$phpmailer->sendEmail(
    $recipients,
    'Körlevel',
    'Ez egy körlevel.'
);
```

### Mellékletek Küldése

```php
<?php
$attachments = [
    [
        'path' => '/path/to/file.pdf',
        'name' => 'dokument.pdf'
    ],
    '/path/to/image.jpg'  // Név nélkül
];

$phpmailer->sendEmail(
    'recipient@example.com',
    'Email melléklettel',
    'Ez az email tartalmaz mellékleteket.',
    true,
    $attachments
);
```

### CC és BCC Címzettek

```php
<?php
$phpmailer->sendEmail(
    'primary@example.com',
    'Email CC és BCC-vel',
    'Ez az email tartalmaz másolatot.',
    true,
    [],                           // Mellékletek
    ['cc@example.com'],           // CC címzettek
    ['bcc@example.com']           // BCC címzettek
);
```

### Teszt Email

```php
<?php
// Gyors teszt email küldése
$phpmailer = Yii::$app->phpmailer;
$result = $phpmailer->sendTestEmail('test@example.com');

if ($result) {
    echo "Teszt email sikeresen elküldve!";
}
```

### Közvetlen PHPMailer Hozzáférés

```php
<?php
// PHPMailer példány közvetlen használata
$mailer = Yii::$app->phpmailer->getMailer();

// PHPMailer specifikus beállítások
$mailer->addReplyTo('reply@example.com', 'Reply Name');
$mailer->isHTML(true);
$mailer->Subject = 'Custom email';
$mailer->Body = 'Custom email content';
$mailer->addAddress('recipient@example.com');

$mailer->send();
```

## Komponens Konfiguráció

### PHPMailer Komponens (mindkét környezet) - Egyetlen Email Rendszer

```php
'phpmailer' => [
    'host' => env('SMTP_HOST', 'localhost'),
    'port' => env('SMTP_PORT', 587),
    'username' => env('SMTP_USERNAME', ''),
    'password' => env('SMTP_PASSWORD', ''),
    'encryption' => env('SMTP_ENCRYPTION', 'tls'),
    'fromEmail' => env('SMTP_FROM_EMAIL', 'noreply@example.com'),
    'fromName' => env('SMTP_FROM_NAME', 'Application'),
    'charset' => env('SMTP_CHARSET', 'UTF-8'),
    'debug' => env('YII_DEBUG', false), // dev: true, prod: false
],
```

**Különbségek környezetek között:**
- **Dev:** `debug=true` (részletes logolás)
- **Prod:** `debug=false` (minimális logolás)

**Megjegyzés:** A `mailer` komponens teljesen el lett távolítva, nincs wrapper vagy átirányítás. Minden email küldés közvetlenül a PHPMailer komponensen keresztül történik.

## Komponens Tulajdonságok

### PHPMailerComponent Tulajdonságok

| Tulajdonság | Típus | Alapértelmezett | Leírás |
|-------------|-------|-----------------|---------|
| host | string | - | SMTP szerver címe |
| port | int | 587 | SMTP port |
| username | string | - | SMTP felhasználónév |
| password | string | - | SMTP jelszó |
| encryption | string | 'tls' | Titkosítás (tls/ssl) |
| fromEmail | string | - | Alapértelmezett feladó email |
| fromName | string | - | Alapértelmezett feladó név |
| charset | string | 'UTF-8' | Karakterkódolás |
| debug | bool | false | Debug mód |

### Metódusok

| Metódus | Paraméterek | Visszatérés | Leírás |
|---------|-------------|-------------|---------|
| sendEmail() | $to, $subject, $body, $isHTML, $attachments, $cc, $bcc | bool | Email küldése |
| sendTestEmail() | $to | bool | Teszt email küldése |
| getMailer() | - | PHPMailer | PHPMailer példány lekérése |

## Hibakezelés

```php
<?php
try {
    $result = Yii::$app->phpmailer->sendEmail(
        'recipient@example.com',
        'Test Subject',
        'Test Body'
    );
    
    if (!$result) {
        throw new Exception('Email küldése sikertelen');
    }
    
} catch (Exception $e) {
    // Hiba naplózása
    Yii::error('Email küldési hiba: ' . $e->getMessage(), __METHOD__);
    
    // Felhasználói hibaüzenet
    Yii::$app->session->setFlash('error', 'Az email küldése sikertelen volt.');
}
```

## SMTP Szolgáltatók Konfigurációja

### Gmail SMTP

```env
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=your-app-password
SMTP_ENCRYPTION=tls
```

**Megjegyzés:** Gmail esetén app-specific jelszót kell használni!

### Outlook/Hotmail SMTP

```env
SMTP_HOST=smtp-mail.outlook.com
SMTP_PORT=587
SMTP_USERNAME=your-email@outlook.com
SMTP_PASSWORD=your-password
SMTP_ENCRYPTION=tls
```

### Yahoo SMTP

```env
SMTP_HOST=smtp.mail.yahoo.com
SMTP_PORT=587
SMTP_USERNAME=your-email@yahoo.com
SMTP_PASSWORD=your-app-password
SMTP_ENCRYPTION=tls
```

## Biztonsági Megfontolások

1. **Jelszó Biztonság**: Soha ne tárold a SMTP jelszavakat a kódban!
2. **App-Specific Jelszavak**: Gmail és más szolgáltatók esetén használj app-specific jelszavakat
3. **Környezeti Változók**: Mindig .env fájlban tárold a hitelesítési adatokat
4. **HTTPS**: Production környezetben mindig használj HTTPS-t
5. **Rate Limiting**: Implementálj rate limiting-et az email küldéshez

## Tesztelés

### Email Küldés Tesztelése

```php
<?php
// Alapvető teszt email küldése
$phpmailer = Yii::$app->phpmailer;

try {
    $result = $phpmailer->sendTestEmail('your-test@example.com');
    
    if ($result) {
        echo "Teszt email sikeresen elküldve!";
    } else {
        echo "Teszt email küldése sikertelen.";
    }
    
} catch (\Exception $e) {
    echo "Hiba: " . $e->getMessage();
}
```

### Komponens Ellenőrzése

```php
<?php
// Ellenőrizzük, hogy a PHPMailer komponens elérhető-e
$phpmailer = Yii::$app->phpmailer;
echo get_class($phpmailer); // common\components\PHPMailerComponent

// A mailer komponens nem létezik többé
try {
    $mailer = Yii::$app->mailer;
} catch (\Exception $e) {
    echo "A mailer komponens nem található - ez helyes!";
}
```

## Troubleshooting

### Gyakori Hibák

1. **SMTP Connect Failed**
   - Ellenőrizd a host és port beállításokat
   - Ellenőrizd a hálózati kapcsolatot

2. **Authentication Failed**
   - Ellenőrizd a felhasználónevet és jelszót
   - Gmail esetén használj app-specific jelszót

3. **SSL/TLS Hibák**
   - Ellenőrizd a titkosítási beállításokat
   - Győződj meg róla, hogy a szerver támogatja a kiválasztott titkosítást

### Debug Mód

Development környezetben kapcsold be a debug módot:

```env
YII_DEBUG=true
```

Ez részletes SMTP kommunikációs logokat fog generálni.

## Teljesítmény Optimalizálás

1. **Connection Reuse**: A komponens újrahasznosítja a SMTP kapcsolatokat
2. **Memory Management**: Nagy mennyiségű email küldésénél figyelj a memóriahasználatra
3. **Queue System**: Nagy volumenű email küldéshez használj queue rendszert

## Verzióinformációk

- **PHPMailer verzió**: 6.10+
- **Yii2 kompatibilitás**: 2.0.45+
- **PHP verzió**: 7.4+

## Összefoglalás

### 🎯 Mi Történt?

1. **PHPMailer Integráció**: Hozzáadtuk a `phpmailer/phpmailer` csomagot
2. **Clean Architektúra**: Teljes eltávolítottuk a `mailer` komponenst
3. **Single Email System**: Csak PHPMailer van jelen, nincs wrapper vagy abstrakció
4. **ContactForm Frissítés**: Az egyetlen meglévő email használat át lett állítva PHPMailer-re

### ✅ Előnyök

- **Ultra Clean Architecture**: Csak 1 email rendszer, nincs confusion
- **Teljes PHPMailer Kontroll**: Minden feature és opció elérhető
- **Symfony Mailer Teljes Kiiktatás**: Teljesen el lett távolítva
- **Egyértelmű API**: Csak egy módja van az email küldésnek
- **Init Project Ready**: Tiszta start a projekt fejlesztéséhez

### 🔧 Használat

```php
// CSAK EGY módja van az email küldésnek:

// PHPMailer komponens - egyetlen email API
Yii::$app->phpmailer->sendEmail('test@example.com', 'Tárgy', 'Tartalom');

// A Yii::$app->mailer TÖBBÉ NEM LÉTEZIK - ez a cél volt!
```

## Támogatás

További kérdések esetén fordulj a fejlesztő csapathoz: Web Solutions Hungary Kft. 