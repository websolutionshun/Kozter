# Beüzemelés

1. `composer install`
2. `cp backend/web/.htaccess.sample backend/web/.htaccess`
3. `cp frontend/web/.htaccess.sample frontend/web/.htaccess`
4. `cp .env.sample .env`
5. `php init`
6. Adatbázis létrehozása utf8bm4_general_ci karakterkészlettel
7. `php yii migrate/up --interactive=0`


# TODO:

# Admin felület:
Wordpress alap funkciók felépítése:
- Bejegyzések
- Kategóriák
- Címkék
- Felhasználók
    - Felhasználók kezelése
    - Szerepkörök kezelése
    - Jogosultságok kezelése
- Média kezelése
- Rendszerbeállítások
- Profilbeállítások