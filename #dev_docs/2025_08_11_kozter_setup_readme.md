# Kozter Projekt Setup Dokumentáció
**Dátum**: 2025. augusztus 11.  
**Fejlesztő**: Web Solutions Hungary Kft.

## Projekt áttekintés
A Kozter egy Yii2 Advanced keretrendszeren alapuló webalkalmazás, amely frontend és backend (admin) felülettel rendelkezik.

## Technikai specifikáció
- **Keretrendszer**: Yii2 Advanced Project Template (v2.0.53)
- **PHP verzió**: 5.6.0+ (ajánlott: 7.4+)
- **Adatbázis**: MySQL
- **Webszerver**: Apache (WAMP)

## Telepítés lépései

### 1. Project inicializálás
```bash
composer create-project --prefer-dist yiisoft/yii2-app-advanced .
php init --env=Development --overwrite=All --delete=All
```

### 2. Adatbázis konfiguráció
Fájl: `common/config/main-local.php`
```php
'db' => [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=' . env('DB_HOST', 'localhost') . ';dbname=' . env('DB_NAME', 'kozter-yii'),
    'username' => env('DB_USER', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8',
],
```

### SMTP konfiguráció
```php
'mailer' => [
    'class' => \yii\symfonymailer\Mailer::class,
    'viewPath' => '@common/mail',
    'useFileTransport' => env('MAILER_USE_FILE_TRANSPORT', false),
    'transport' => [
        'scheme' => env('SMTP_SECURE', 'tls'),
        'host' => env('SMTP_HOST', 'sandbox.smtp.mailtrap.io'),
        'username' => env('SMTP_USERNAME', ''),
        'password' => env('SMTP_PASSWORD', ''),
        'port' => env('SMTP_PORT', 2525),
    ],
],
```

### 3. Migrációk futtatása
```bash
php yii migrate/up --interactive=0
```

## Virtual Host beállítások

### Apache Virtual Host konfiguráció
A WAMP `httpd-vhosts.conf` fájlban az alábbi beállítások szükségesek:

#### Frontend (kozter.test)
```apache
<VirtualHost *:80>
    ServerName kozter.test
    DocumentRoot "C:/wamp64/www/github/Kozter/frontend/web/"
    
    <Directory "C:/wamp64/www/github/Kozter/frontend/web/">
        RewriteEngine on
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . index.php
        DirectoryIndex index.php
        Require all granted
    </Directory>
</VirtualHost>
```

#### Backend/Admin (kozter-admin.test)
```apache
<VirtualHost *:80>
    ServerName kozter-admin.test
    DocumentRoot "C:/wamp64/www/github/Kozter/backend/web/"
    
    <Directory "C:/wamp64/www/github/Kozter/backend/web/">
        RewriteEngine on
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . index.php
        DirectoryIndex index.php
        Require all granted
    </Directory>
</VirtualHost>
```

### Windows hosts fájl
Fájl: `C:\Windows\System32\Drivers\etc\hosts`
```
127.0.0.1   kozter.test
127.0.0.1   kozter-admin.test
```

## Projekt struktúra
```
Kozter/
├── backend/          # Admin felület
│   ├── web/         # Web root admin-hoz
│   └── ...
├── frontend/        # Felhasználói felület
│   ├── web/         # Web root frontend-hez
│   └── ...
├── common/          # Közös komponensek
├── console/         # CLI parancsok
├── vendor/          # Composer dependencies
└── ...
```

## Elérhetőségek
- **Frontend**: http://kozter.test
- **Backend/Admin**: http://kozter-admin.test

## Fontos megjegyzések
- Soha ne használd a `php yii serve --port=8080` megoldást
- Mindig használj `--interactive=0` flag-et a migrate parancsoknál
- A WAMP környezetben virtual host-okat használunk

## Következő lépések
1. Virtual host beállítások alkalmazása az Apache konfigurációban
2. Apache és DNS cache törlése
3. Böngészőben tesztelés
4. Első admin felhasználó létrehozása

## DotEnv konfiguráció

### Telepítés
```bash
composer require vlucas/phpdotenv
```

### Környezeti változók
A projekt mostantól `.env` fájlt használ a konfigurációhoz:
- **Adatbázis**: `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME` (dev és prod környezetekhez)
- **Biztonság**: `FRONTEND_COOKIE_VALIDATION_KEY`, `BACKEND_COOKIE_VALIDATION_KEY`
- **Email**: `ADMIN_EMAIL`, `SUPPORT_EMAIL`, `SENDER_EMAIL`, `SENDER_NAME`
- **SMTP**: `SMTP_HOST`, `SMTP_USERNAME`, `SMTP_PASSWORD`, `SMTP_SECURE`, `SMTP_PORT`, `SMTP_CHARSET`, `SMTP_FROM`, `SMTP_SENDER_NAME`
- **Mailer**: `MAILER_USE_FILE_TRANSPORT`
- **Fejlesztői eszközök**: `ENABLE_DEBUG`, `ENABLE_GII`
- **Környezet**: `YII_ENV`, `YII_DEBUG`

### SMTP beállítások
- **Dev környezet**: Mailtrap.io sandbox
- **Prod környezet**: SMTP2GO (kommentben)

### Használat
1. Másold át a `.env.sample` fájlt `.env` néven
2. Módosítsd az értékeket a környezetednek megfelelően
3. A `.env` fájl nincs verziókezelésben (`.gitignore`-ban kizárva)

### DotEnv helper függvény
```php
env('KULCS', 'alapértelmezett_érték')
```

## Adatbázis táblák
A migrációk után az alábbi táblák kerültek létrehozásra:
- `migration` - migrációs előzmények
- `user` - felhasználói adatok (verification_token mezővel) 