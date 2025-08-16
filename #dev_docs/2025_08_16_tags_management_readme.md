# Címkék kezelése modul - Fejlesztői dokumentáció

**Dátum:** 2025. 08. 16.  
**Fejlesztő:** Web Solutions Hungary Kft.

## Áttekintés

A címkék kezelése modul egy egyszerűsített címkerendszert valósít meg a Yii Advanced keretrendszerben. A modul teljes CRUD funkcionalitást biztosít címkék kezeléséhez, jogosultságkezeléssel és modern felhasználói felülettel. A kategóriákkal ellentétben a címkék nem támogatnak hierarchikus struktúrát (szülő-gyerek kapcsolatok).

## Főbb funkciók

### 1. Címke kezelési műveletek
- **Megtekintés**: Címkék listázása táblázatos formában
- **Létrehozás**: Új címkék hozzáadása színkóddal
- **Szerkesztés**: Meglévő címkék módosítása
- **Törlés**: Egyedi és tömeges törlési lehetőségek
- **Gyors szerkesztés**: AJAX alapú inline szerkesztés
- **Állapot váltás**: Aktív/inaktív státusz módosítás

### 2. Színkódolás funkciók
- Egyedi színkód beállítás (hex formátum)
- Előre definiált színpaletta
- Véletlenszerű színválasztó
- Színes megjelenítés a felületen

### 3. WordPress stílusú felhasználói felület
- Táblázatos megjelenítés bulk műveletekkel
- Gyors hozzáadás sidebar
- Inline szerkesztés lehetőség
- Statisztikai információk
- Színes vizuális megjelenítés

## Technikai implementáció

### Adatbázis struktúra

#### Tags tábla (`{{%tags}}`)
```sql
- id (INTEGER, PRIMARY KEY)
- name (VARCHAR(255), NOT NULL)
- slug (VARCHAR(255), NOT NULL, UNIQUE)
- description (TEXT)
- color (VARCHAR(7), DEFAULT '#007acc') - Hex színkód
- count (INTEGER, DEFAULT 0)
- status (SMALLINT, DEFAULT 1)
- created_at (INTEGER, NOT NULL)
- updated_at (INTEGER, NOT NULL)
```

#### Indexek
- `idx-tags-slug`: URL slug index
- `idx-tags-status`: Állapot index

### Model funkcionalitás (`common/models/Tag.php`)

#### Validációk
- `name`: kötelező mező
- `slug`: egyedi URL név (automatikus generálás)
- `color`: hex színkód formátum validáció (#FFFFFF)
- `status`: csak 0 vagy 1 értékek

#### Behaviors
- `TimestampBehavior`: automatikus created_at/updated_at
- `SluggableBehavior`: automatikus slug generálás

#### Speciális funkciók
- `getStatusOptions()`: állapot opciók
- `getDefaultColors()`: alapértelmezett színpaletta
- `getList()`: dropdown listához optimalizált lekérdezés

### Controller funkcionalitás (`backend/controllers/TagController.php`)

#### Akciók
- `actionIndex()`: címkék listázása
- `actionCreate()`: új címke létrehozása
- `actionUpdate()`: címke szerkesztése
- `actionDelete()`: címke törlése
- `actionBulkDelete()`: tömeges törlés
- `actionQuickEdit()`: AJAX gyors szerkesztés
- `actionToggleStatus()`: állapot váltás

#### Jogosultságellenőrzés
- Minden akcióhoz megfelelő jogosultság szükséges
- Admin panel jogosultság felülírja a specifikus jogokat

### View implementáció

#### `index.php`
- WordPress stílusú táblázatos megjelenítés
- Tömeges műveletek támogatása
- AJAX-os gyors szerkesztés
- Színes címke megjelenítés
- Gyors hozzáadás sidebar
- Statisztikai összegző

#### `create.php`
- Teljes körű űrlap új címke létrehozásához
- Automatikus slug generálás
- Színválasztó funkcionalitás
- Előre definiált színpaletta

#### `update.php`
- Címke szerkesztési űrlap
- Jelenlegi értékek megjelenítése
- Színválasztó funkcionalitás
- Létrehozási/módosítási információk

## Routing konfiguráció

### Magyar nyelvű útvonalak
- `/cimkek` → Címkék listája
- `/cimkek/letrehozas` → Új címke létrehozása
- `/cimkek/{id}/szerkesztes` → Címke szerkesztése
- `/cimkek/{id}/torles` → Címke törlése
- `/cimkek/tomeges-torles` → Tömeges törlés
- `/cimkek/gyors-szerkesztes/{id}` → AJAX gyors szerkesztés
- `/cimkek/allapot-valtas/{id}` → Állapot váltás

## Jogosultságrendszer integráció

### Jogosultságok
A migrációban automatikusan létrejönnek a következő jogosultságok:
- `tag_view`: Címkék megtekintése
- `tag_create`: Új címke létrehozása
- `tag_edit`: Címkék szerkesztése
- `tag_delete`: Címkék törlése

### Menüintegráció
A címkék kezelése menüpont automatikusan megjelenik a backend navigációban jogosultság alapú ellenőrzéssel.

## JavaScript funkciók

### AJAX műveletek
- Tömeges művelet kezelés
- Gyors szerkesztés (inline editing)
- Állapot váltás
- Valós idejű slug generálás

### Színválasztó funkciók
- Előre definiált színpaletta
- Véletlenszerű színgenerátor
- Vizuális színmegjelenítés
- Hex kód validáció

### Példa használat
```javascript
// Állapot váltás
function toggleStatus(id) {
    $.post('/cimkek/allapot-valtas/' + id, {
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
A címkék menüpont automatikusan megjelenik a backend navigációban a jogosultságok alapján.

## Fejlesztési megjegyzések

### Egyszerűsített struktura
A modul a kategóriákkal ellentétben nem támogatja:
- Hierarchikus kapcsolatokat (szülő-gyerek)
- Körkörös hivatkozások ellenőrzését
- Hierarchikus listázást

### Színkódolás
A címkék egyedi színkóddal rendelkeznek, amely:
- Hex formátumban (#FFFFFF) tárolódik
- Vizuálisan megjelenik a felületen
- Alapértelmezett színpalettából választható
- Véletlenszerűen generálható

### Extensibility
A rendszer könnyen bővíthető:
- Új színek hozzáadása a `getDefaultColors()` metódusban
- Címke kategóriák bevezetése
- Kapcsolatok más entitásokkal (cikkek, termékek, stb.)
- Címke statisztikák bővítése

## Különbségek a kategóriákhoz képest

| Funkció | Kategóriák | Címkék |
|---------|------------|--------|
| Hierarchikus struktúra | ✅ | ❌ |
| Szülő-gyerek kapcsolat | ✅ | ❌ |
| Színkódolás | ❌ | ✅ |
| Egyszerűsített kezelés | ❌ | ✅ |
| WordPress stílusú UI | ✅ | ✅ |
| CRUD műveletek | ✅ | ✅ |
| Jogosultságkezelés | ✅ | ✅ |

## Jövőbeli fejlesztési lehetőségek

1. **Címke kapcsolatok**: Cikkekhez, termékekhez való hozzárendelés
2. **Címke felhő**: Népszerűség alapú megjelenítés
3. **Automatikus címke javaslatok**: AI alapú címke ajánlások
4. **Címke kategóriák**: Címkék csoportosítása
5. **Statisztikai dashboard**: Részletes használati adatok
6. **Import/Export**: Címkék tömeges kezelése
7. **Színes szűrés**: Címkék szín alapú szűrése

## Tesztelés

A modul teszteléséhez:
1. Lépj be az admin felületre
2. Navigálj a "Címkék kezelése" menüpontra
3. Tesztelj minden CRUD műveletet
4. Ellenőrizd a jogosultságokat különböző szerepkörökkel
5. Teszteld az AJAX funkciókat
6. Próbáld ki a színválasztó funkciókat
