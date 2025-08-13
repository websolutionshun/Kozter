# Kategóriák kezelése modul - Fejlesztői dokumentáció

**Dátum:** 2025. 08. 13.  
**Fejlesztő:** Web Solutions Hungary Kft.

## Áttekintés

A kategóriák kezelése modul egy WordPress stílusú kategóriarendszert valósít meg a Yii Advanced keretrendszerben. A modul teljes CRUD funkcionalitást biztosít hierarchikus kategóriák kezeléséhez, jogosultságkezeléssel és modern felhasználói felülettel.

## Főbb funkciók

### 1. Kategória kezelési műveletek
- **Megtekintés**: Kategóriák listázása hierarchikus táblázatban
- **Létrehozás**: Új kategóriák hozzáadása szülő-gyerek kapcsolatokkal
- **Szerkesztés**: Meglévő kategóriák módosítása
- **Törlés**: Egyedi és tömeges törlési lehetőségek
- **Gyors szerkesztés**: AJAX alapú inline szerkesztés
- **Állapot váltás**: Aktív/inaktív státusz módosítás

### 2. Hierarchikus funkciók
- Szülő-gyerek kategória kapcsolatok
- Körkörös hivatkozások ellenőrzése
- Hierarchikus lista generálás
- Teljes útvonal megjelenítés

### 3. WordPress stílusú felhasználói felület
- Táblázatos megjelenítés bulk műveletekkel
- Gyors hozzáadás sidebar
- Inline szerkesztés lehetőség
- Statisztikai információk

## Technikai implementáció

### Adatbázis struktúra

#### Categories tábla (`{{%categories}}`)
```sql
- id (INTEGER, PRIMARY KEY)
- name (VARCHAR(255), NOT NULL)
- slug (VARCHAR(255), NOT NULL, UNIQUE)
- description (TEXT)
- parent_id (INTEGER, NULL, FK to categories.id)
- count (INTEGER, DEFAULT 0)
- status (SMALLINT, DEFAULT 1)
- created_at (INTEGER, NOT NULL)
- updated_at (INTEGER, NOT NULL)
```

#### Indexek és kapcsolatok
- `idx-categories-parent_id`: Szülő kategória index
- `idx-categories-slug`: URL slug index
- `idx-categories-status`: Állapot index
- `fk-categories-parent_id`: Külső kulcs a szülő kategóriához

### Model funkcionalitás (`common/models/Category.php`)

#### Validációk
- Név kötelező
- Slug egyediség
- Szülő kategória validáció (nem lehet saját maga)
- Körkörös hivatkozás ellenőrzése

#### Behavior-ök
- `TimestampBehavior`: Automatikus időbélyegzés
- `SluggableBehavior`: Automatikus slug generálás

#### Speciális metódusok
- `getHierarchicalList()`: Hierarchikus kategória lista
- `getFullPath()`: Teljes útvonal lekérése
- `updateCount()`: Elemek számának frissítése
- `beforeDelete()`: Törlés előtti gyerek kategóriák kezelése

### Controller funkcionalitás (`backend/controllers/CategoryController.php`)

#### Jogosultságkezelés
- `category_view`: Kategóriák megtekintése
- `category_create`: Új kategória létrehozása
- `category_edit`: Kategóriák szerkesztése
- `category_delete`: Kategóriák törlése

#### Műveletek
- `actionIndex()`: Lista megjelenítés
- `actionCreate()`: Új kategória létrehozás
- `actionUpdate()`: Kategória szerkesztés
- `actionDelete()`: Kategória törlés
- `actionBulkDelete()`: Tömeges törlés
- `actionQuickEdit()`: AJAX gyors szerkesztés
- `actionToggleStatus()`: Állapot váltás

### View fájlok

#### `backend/views/category/index.php`
- WordPress stílusú táblázatos megjelenítés
- Bulk műveletek (tömeges törlés)
- AJAX alapú gyors szerkesztés
- Gyors hozzáadás sidebar
- Statisztikai információk

#### `backend/views/category/create.php`
- Kategória létrehozó form
- Automatikus slug generálás
- Szülő kategória választás
- Tippek és meglévő kategóriák megjelenítése

#### `backend/views/category/update.php`
- Kategória szerkesztő form
- Kategória információk megjelenítése
- Alkategóriák listázása
- Műveletek (duplikálás, állapot váltás)

## URL útvonalak

### Magyar nyelvű útvonalak
- `/kategoriak` → Kategóriák listája
- `/kategoriak/letrehozas` → Új kategória létrehozása
- `/kategoriak/{id}/szerkesztes` → Kategória szerkesztése
- `/kategoriak/{id}/torles` → Kategória törlése
- `/kategoriak/tomeges-torles` → Tömeges törlés
- `/kategoriak/gyors-szerkesztes/{id}` → AJAX gyors szerkesztés
- `/kategoriak/allapot-valtas/{id}` → Állapot váltás

## Jogosultságrendszer integráció

### Jogosultságok
A migrációban automatikusan létrejönnek a következő jogosultságok:
- `category_view`: Kategóriák megtekintése
- `category_create`: Új kategória létrehozása
- `category_edit`: Kategóriák szerkesztése
- `category_delete`: Kategóriák törlése

### Menüintegráció
A kategóriák kezelése menüpont automatikusan megjelenik a backend navigációban jogosultság alapú ellenőrzéssel.

## JavaScript funkciók

### AJAX műveletek
- Tömeges művelet kezelés
- Gyors szerkesztés (inline editing)
- Állapot váltás
- Valós idejű slug generálás

### Példa használat
```javascript
// Állapot váltás
function toggleStatus(id) {
    $.post('/kategoriak/allapot-valtas/' + id, {
        _csrf: 'csrf_token'
    }).done(function(data) {
        // UI frissítés
    });
}
```

## Telepítés és konfiguráció

### 1. Migrációk futtatása
```bash
php yii migrate/up --interactive=0
```

### 2. Jogosultságok hozzárendelése
A migrációk automatikusan létrehozzák a szükséges jogosultságokat. Ezeket a szerepkörök kezelésében lehet hozzárendelni a megfelelő szerepkörökhöz.

### 3. Menü ellenőrzése
A kategóriák menüpont automatikusan megjelenik a backend navigációban a jogosultságok alapján.

## Fejlesztési megjegyzések

### WordPress kompatibilitás
A modul a WordPress kategóriarendszer logikáját követi:
- Hierarchikus kategóriák támogatása
- Slug alapú URL-ek
- Bulk műveletek
- Gyors szerkesztés funkció
- Kategória számlálók

### Extensibility
A rendszer könnyen bővíthető:
- Új metaadatok hozzáadása a modellhez
- További AJAX műveletek implementálása
- Kategória típusok bevezetése
- Media kapcsolatok hozzáadása

### Performance optimalizáció
- Indexek használata gyakori lekérdezésekhez
- AJAX alapú műveletek a jobb UX-ért
- Lazy loading a nagy kategória listák esetén

## Tesztelés

### Funkcionális tesztek
- Kategória CRUD műveletek
- Hierarchikus kapcsolatok
- Jogosultság ellenőrzések
- AJAX műveletek

### Edge case-ek
- Körkörös hivatkozások kezelése
- Törlés során gyerek kategóriák kezelése
- Egyedi slug biztosítása
- Nagy mennyiségű adat kezelése

## Támogatás

A modul teljes mértékben kompatibilis a Yii Advanced keretrendszerrel és követi annak best practice-eit. A kód tiszta, jól dokumentált és könnyen karbantartható.

### Fejlesztői kontakt
**Web Solutions Hungary Kft.**  
Kategóriák kezelése modul implementáció
