# Felhasználó- és Jogosultságkezelő Rendszer

**Létrehozva:** 2025. 08. 12.  
**Fejlesztő:** Web Solutions Hungary Kft.

## Áttekintés

Ez a dokumentáció leírja a Köztér projekt számára kifejlesztett komplex felhasználó- és jogosultságkezelő rendszert. A rendszer lehetővé teszi a felhasználók, szerepkörök és jogosultságok rugalmas kezelését az admin felületen keresztül.

## Főbb Funkciók

### 1. Felhasználókezelés
- **Felhasználók listázása**: Táblázatos megjelenítés szűrési és lapozási opciókkal
- **Új felhasználó létrehozása**: Szerepkörök hozzárendelésével
- **Felhasználó szerkesztése**: Adatok és szerepkörök módosítása
- **Felhasználó törlése**: Soft delete (státusz változtatás)
- **Jelszó visszaállítás**: Admin által kezdeményezett jelszó módosítás

### 2. Szerepkörkezelés
- **Szerepkörök listázása**: Jogosultságok és felhasználók számának megjelenítésével
- **Új szerepkör létrehozása**: Jogosultságok kiválasztásával
- **Szerepkör szerkesztése**: Név, leírás és jogosultságok módosítása
- **Szerepkör törlése**: Védelem az alapértelmezett szerepkörök ellen

### 3. Jogosultságkezelés
- **Interaktív táblázatos nézet**: Sorok = jogosultságok, oszlopok = szerepkörök
- **Real-time switch kapcsolók**: Azonnali mentés AJAX-szal
- **Kategóriák szerinti csoportosítás**: Áttekinthetőbb megjelenítés
- **Tömeges műveletek**: Mind kijelölés/törlés funkciók

## Adatbázis Struktúra

### Táblák

#### `roles` - Szerepkörök
- `id` (PK)
- `name` (VARCHAR 50, UNIQUE)
- `description` (TEXT)
- `created_at`, `updated_at` (TIMESTAMP)

#### `permissions` - Jogosultságok
- `id` (PK)
- `name` (VARCHAR 100, UNIQUE)
- `description` (TEXT)
- `category` (VARCHAR 50)
- `created_at`, `updated_at` (TIMESTAMP)

#### `user_roles` - Felhasználó-szerepkör kapcsolatok
- `id` (PK)
- `user_id` (FK → users.id)
- `role_id` (FK → roles.id)
- `created_at` (TIMESTAMP)

#### `role_permissions` - Szerepkör-jogosultság kapcsolatok
- `id` (PK)
- `role_id` (FK → roles.id)
- `permission_id` (FK → permissions.id)
- `created_at` (TIMESTAMP)

### Alapértelmezett Adatok

#### Szerepkörök
1. **admin** - Teljes rendszeradminisztrátori jogosultságok
2. **szerkesztő** - Tartalom szerkesztési jogosultságok
3. **szerző** - Alapvető tartalomkészítési jogosultságok

#### Jogosultság Kategóriák
- **Felhasználókezelés**: user_view, user_create, user_edit, user_delete
- **Szerepkörkezelés**: role_view, role_create, role_edit, role_delete
- **Jogosultságkezelés**: permission_view, permission_manage
- **Rendszer**: system_settings, admin_panel

## Modellek

### User Model Bővítések
```php
// Új metódusok
public function getRoles()              // Szerepkörök lekérése
public function hasRole($roleName)      // Szerepkör ellenőrzés
public function hasPermission($name)    // Jogosultság ellenőrzés
public function addRole($roleId)        // Szerepkör hozzáadása
public function removeRole($roleId)     // Szerepkör eltávolítása
```

### Role Model
```php
public function getPermissions()        // Jogosultságok lekérése
public function hasPermission($name)    // Jogosultság ellenőrzés
public function addPermission($id)      // Jogosultság hozzáadása
public function removePermission($id)   // Jogosultság eltávolítása
```

### Permission Model
```php
public static function getCategories()  // Kategóriák listája
public static function getByCategories() // Kategóriák szerint csoportosítás
```

## Kontrollerek

### UserController
- `actionIndex()` - Felhasználók listázása
- `actionView($id)` - Felhasználó részletei
- `actionCreate()` - Új felhasználó létrehozása
- `actionUpdate($id)` - Felhasználó szerkesztése
- `actionDelete($id)` - Felhasználó törlése
- `actionResetPassword($id)` - Jelszó visszaállítás

### RoleController
- `actionIndex()` - Szerepkörök listázása
- `actionView($id)` - Szerepkör részletei
- `actionCreate()` - Új szerepkör létrehozása
- `actionUpdate($id)` - Szerepkör szerkesztése
- `actionDelete($id)` - Szerepkör törlése

### PermissionController
- `actionIndex()` - Jogosultságkezelő főoldal
- `actionToggle()` - AJAX jogosultság kapcsoló
- `actionSetRolePermissions()` - Szerepkör összes jogosultságának beállítása
- `actionSetPermissionRoles()` - Jogosultság összes szerepkörének beállítása

## Nézetek (Views)

### Felhasználókezelés (/user/)
- `index.php` - Felhasználók listája táblázatban
- `create.php` - Új felhasználó form szerepkör kiválasztással
- `update.php` - Szerkesztés form
- `view.php` - Részletes nézet + jelszó reset modal

### Szerepkörkezelés (/role/)
- `index.php` - Szerepkörök listája jogosultság összefoglalóval

### Jogosultságkezelés (/permission/)
- `index.php` - Interaktív jogosultság mátrix táblázat

## Biztonság és Jogosultságok

### Hozzáférés Ellenőrzés
```php
// Minden kontroller használja az AccessControl filtert
'matchCallback' => function ($rule, $action) {
    return Yii::$app->user->identity->hasPermission('admin_panel');
}
```

### CSRF Védelem
- Minden POST kérés CSRF tokennel védett
- AJAX kérések is tartalmazzák a CSRF tokent

### Alapértelmezett Jogosultság Elosztás
- **Admin**: Minden jogosultság
- **Szerkesztő**: user_view, user_create, user_edit, role_view, permission_view, admin_panel
- **Szerző**: user_view, admin_panel

## Felhasználói Felület

### Design Alapok
- **Tabler.io** framework használata
- Reszponzív design
- Modern ikonok (Tabler Icons)
- Bootstrap 5 komponensek

### Speciális Funkciók
- **Real-time switch kapcsolók**: Azonnali mentés visszajelzéssel
- **Master checkbox**: Szerepkör összes jogosultságának gyors kapcsolása
- **Tömeges műveletek**: Mind kijelölés/törlés gombok
- **Intelligent UI**: Checkbox állapotok automatikus frissítése

## URL Struktúra

```
/user/              - Felhasználók listája
/user/create        - Új felhasználó
/user/view/1        - Felhasználó megtekintése
/user/update/1      - Felhasználó szerkesztése
/user/delete/1      - Felhasználó törlése
/user/reset-password/1 - Jelszó visszaállítás

/role/              - Szerepkörök listája
/role/create        - Új szerepkör
/role/view/1        - Szerepkör megtekintése
/role/update/1      - Szerepkör szerkesztése
/role/delete/1      - Szerepkör törlése

/permission/        - Jogosultságkezelő főoldal
/permission/toggle  - AJAX jogosultság kapcsoló
```

## Navigáció

A backend főmenüben az "Adminisztráció" dropdown alatt:
- **Felhasználók kezelése** → /user/
- **Szerepkörök** → /role/
- **Jogosultságkezelés** → /permission/
- **Rendszerbeállítások** → (placeholder)

## Műszaki Megjegyzések

### Teljesítmény Optimalizációk
- Eager loading használata (`with('roles')`, `with('permissions')`)
- AJAX alapú jogosultság kapcsolók a teljes oldal újratöltés elkerülésére
- Lapozás és szűrés támogatása nagy adatmennyiség esetén

### Extensibility (Bővíthetőség)
- Új jogosultságok könnyen hozzáadhatók a permissions táblához
- Új szerepkörök dinamikusan létrehozhatók
- A jogosultságkezelő táblázat automatikusan alkalmazkodik új szerepkörökhöz

### Validációk
- Egyedi felhasználónév és email cím
- Jelszó minimum 6 karakter
- Alapértelmezett szerepkörök védettek a törléstől
- Használatban lévő szerepkörök nem törölhetők

## Jövőbeli Fejlesztési Lehetőségek

1. **Audit Log**: Jogosultság változások naplózása
2. **Időzített jogosultságok**: Lejárati dátummal rendelkező jogosultságok
3. **Hierarchikus szerepkörök**: Szerepkör öröklődés
4. **API jogosultságok**: REST API végpontok védelme
5. **Bulk műveletek**: Több felhasználó tömeges szerkesztése
6. **Import/Export**: CSV alapú felhasználó import/export
7. **2FA integráció**: Kétfaktoros hitelesítés támogatása

## Karbantartás

### Adatbázis Migrációk
```bash
# Migrációk futtatása
php yii migrate/up --interactive=0

# Új migráció létrehozása
php yii migrate/create migration_name --interactive=0
```

### Hibaelhárítás
- Jogosultság problémák esetén ellenőrizze a `user_roles` és `role_permissions` táblákat
- Cache ürítés szükséges lehet modell változtatások után
- AJAX hibák esetén ellenőrizze a CSRF token beállításokat

---

*Ez a dokumentáció az elkészült felhasználó- és jogosultságkezelő rendszer teljes funkcionalitását és implementációs részleteit tartalmazza.* 