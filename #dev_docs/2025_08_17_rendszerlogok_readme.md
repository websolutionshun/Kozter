# Rendszerlogok Modul - 2025. 08. 17.

**Fejlesztő:** Web Solutions Hungary Kft.

## Áttekintés

Egy átfogó rendszerlog kezelő modul lett létrehozva az admin felülethez, amely lehetővé teszi az alkalmazás különböző eseményeinek naplózását, megjelenítését és kezelését.

## Főbb funkciók

### 1. Log Modell (common/models/Log.php)
- **Szintek:** error, warning, info, success
- **Adatok:** kategória, üzenet, felhasználó, IP cím, URL, metódus, kiegészítő adatok JSON formátumban
- **Shortcut metódusok:** `Log::info()`, `Log::error()`, `Log::warning()`, `Log::success()`
- **Automatikus adatgyűjtés:** felhasználó, IP cím, URL, user agent (webes kérésekhez)

### 2. LogController (backend/controllers/LogController.php)
**Akciók:**
- `index` - Logok listázása szűrési lehetőségekkel
- `view` - Részletes log megtekintése
- `stats` - Statisztikák és összesítések
- `delete` - Egyes log törlése
- `bulk-delete` - Tömeges törlés
- `clear-old` - Régi logok törlése

**Szűrési lehetőségek:**
- Szint szerint (error, warning, info, success)
- Kategória szerint
- Dátum szerint (ma, utolsó 7 nap, 30 nap)
- Szabad szöveges keresés az üzenetekben
- Felhasználó szerint

### 3. View fájlok (backend/views/log/)

#### index.php
- Modern táblázatos megjelenítés Grid widget-tel
- Szűrési űrlap
- Tömeges műveletek
- AJAX-os lista frissítés (Pjax)
- Régi logok törlése modal

#### view.php
- Részletes log információk
- Formázott JSON adatok megjelenítése
- Kapcsolódó felhasználó és műveletek
- Responzív kártyás elrendezés

#### stats.php
- Összesítő kártyák
- Szint szerinti megoszlás
- Top kategóriák
- Heti aktivitás grafikon
- Interaktív elemek és linkek

### 4. Jogosultságok

**Új jogosultságok:**
- `log_view` - Rendszerlogok megtekintése
- `log_manage` - Rendszerlogok kezelése (törlés, statisztikák)

**AccessControl beállítások:**
- Megtekintés: `log_view` vagy `admin_panel` jogosultság
- Kezelés: `log_manage` vagy `admin_panel` jogosultság

### 5. Navigáció

**Menü integráció:**
- Főmenü: "Rendszerlogok" elem hozzáadva
- Gyorsindítók: Rendszerlogok ikon a fejlécben
- URL útvonalak: `/rendszerlogok`, `/rendszerlogok/statisztikak`

## Adatbázis struktúra

### logs tábla
```sql
- id (PRIMARY KEY)
- level (VARCHAR 20) - Log szint
- category (VARCHAR 255) - Kategória
- message (TEXT) - Üzenet
- data (TEXT) - Kiegészítő adatok JSON-ban
- user_id (INT) - Kapcsolódó felhasználó
- ip_address (VARCHAR 45) - IP cím
- user_agent (TEXT) - Böngésző információ
- url (VARCHAR 2048) - Kérés URL
- method (VARCHAR 10) - HTTP metódus
- created_at (INT) - Létrehozás timestamp
```

**Indexek:**
- `idx_logs_level` - Szint szerint
- `idx_logs_category` - Kategória szerint  
- `idx_logs_user_id` - Felhasználó szerint
- `idx_logs_created_at` - Dátum szerint

**Foreign key:**
- `fk_logs_user_id` -> `user.id`

## Használat

### Programozói használat

```php
// Alapvető log létrehozása
Log::info('Felhasználó bejelentkezett', 'auth', ['user_id' => 123]);

// Hiba naplózása
Log::error('Adatbázis kapcsolat hiba', 'database', [
    'error' => $exception->getMessage(),
    'file' => $exception->getFile()
]);

// Sikeres művelet
Log::success('Fájl feltöltés sikeres', 'media', [
    'filename' => $filename,
    'size' => $filesize
]);

// Figyelmeztetés
Log::warning('Alacsony tárterület', 'system', [
    'free_space' => $freeSpace,
    'threshold' => $threshold
]);
```

### Felhasználói felület

1. **Lista oldal** (`/rendszerlogok`)
   - Szűrés szint, kategória, dátum szerint
   - Keresés az üzenetekben
   - Törölhető elemek kiválasztása
   - Lapozás és rendezés

2. **Részletek oldal** (`/rendszerlogok/{id}`)
   - Teljes log információ
   - Formázott JSON adatok
   - Kapcsolódó műveletek

3. **Statisztikák** (`/rendszerlogok/statisztikak`)
   - Összesítő számok
   - Szint szerinti megoszlás
   - Kategória toplista
   - Napi aktivitás grafikon

## Migrációk

1. **m250817_084800_create_logs_table.php** - Logs tábla létrehozása
2. **m250817_085622_add_log_permissions.php** - Log jogosultságok hozzáadása

## Konzol parancsok

### Teszt logok létrehozása
```bash
php yii test/log
```

### Jogosultságok ellenőrzése
```bash
php yii test/permissions
```

## Biztonság

- Jogosultság alapú hozzáférés-vezérlés
- CSRF védelem minden POST kérésnél
- Input validáció és szűrés
- SQL injection védelem Active Record-dal

## Teljesítmény

- Indexelt oszlopok a gyors keresésért
- Lapozás nagy adatmennyiséghez
- AJAX frissítés (Pjax) a jobb UX-ért
- Optimalizált lekérdezések kapcsolódó táblákhoz

## Karbantartás

### Régi logok törlése
- Manuális törlés a felületen keresztül
- Tömeges törlés kiválasztott elemek
- Dátum alapú törlés (7, 14, 30, 60, 90 nap)

### Monitorozás
- Statisztikák oldal rendszeres ellenőrzése
- Hibák és figyelmeztetések követése
- Adatbázis méret figyelése

## Továbbfejlesztési lehetőségek

1. **Email értesítések** - Kritikus hibáknál automatikus email
2. **Export funkció** - CSV/Excel export
3. **Valós idejű dashboard** - WebSocket-es frissítések
4. **Logrotáció** - Automatikus archívum régi logoknak
5. **Szűrő mentés** - Kedvenc szűrők mentése
6. **Grafikus statisztikák** - Chart.js integrációja

## Fájlok

### Újonnan létrehozott fájlok:
- `common/models/Log.php`
- `backend/controllers/LogController.php`
- `backend/views/log/index.php`
- `backend/views/log/view.php`
- `backend/views/log/stats.php`
- `console/migrations/m250817_084800_create_logs_table.php`
- `console/migrations/m250817_085622_add_log_permissions.php`
- `console/controllers/TestController.php`

### Módosított fájlok:
- `backend/config/main.php` - URL útvonalak hozzáadása
- `backend/views/layouts/main.php` - Menü elemek hozzáadása

A rendszerlogok modul teljes mértékben integrálódott a meglévő rendszerbe és készen áll a használatra.
