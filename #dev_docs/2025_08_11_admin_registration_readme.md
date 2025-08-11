# Admin Regisztrációs Rendszer Dokumentáció

**Fejlesztő:** Web Solutions Hungary Kft.  
**Dátum:** 2025. 08. 11.

## Áttekintés

Egy biztonságos admin felhasználó regisztrációs rendszer került implementálásra, amely csak environment változó alapú hitelesítéssel érhető el.

## Funkciók

### 1. Email Alapú Bejelentkezés
- A bejelentkezés mostantól email cím és jelszó alapú
- A `LoginForm` model frissítve lett email validációval
- A backend login view frissítve lett

### 2. Nem Publikus Admin Regisztráció
- URL: `/backend/web/index.php?r=site%2Fadmin-register`
- Csak akkor érhető el, ha a `.env` fájlban beállított `ADMIN_ADD_USER_MANUAL` változó
- WordPress stílusú felhasználónév validáció

### 3. WordPress Stílusú Felhasználónév Validáció
- Csak betűk, számok, pontok (.), kötőjelek (-) és aláhúzások (_) engedélyezettek
- Nem kezdődhet és nem végződhet speciális karakterrel
- Nem tartalmazhat egymás utáni speciális karaktereket

## Environment Változó

### ADMIN_ADD_USER_MANUAL

A `.env` fájlban kell beállítani:

```env
ADMIN_ADD_USER_MANUAL=VcLxvJ3L6Hn9xGBU6ZVrm4CFtik3PJrZRlEsNk4pUz7F2Crczj
```

**Fontos:** Ez a kulcs megváltoztatható, de ajánlott egy hosszú, biztonságos random string használata.

## Fájl Módosítások

### Új fájlok:
- `backend/models/AdminRegistrationForm.php` - Admin regisztrációs form model
- `backend/views/site/admin-register.php` - Admin regisztrációs view
- `#dev_docs/2025_08_11_admin_registration_readme.md` - Ez a dokumentáció

### Módosított fájlok:
- `common/models/LoginForm.php` - Email alapú bejelentkezés
- `common/models/User.php` - `findByEmail()` metódus hozzáadása
- `backend/controllers/SiteController.php` - `actionAdminRegister()` hozzáadása
- `backend/views/site/login.php` - Email mező használata

## Használat

### 1. Environment Beállítása

1. Hozz létre egy `.env` fájlt a projekt gyökerében
2. Add hozzá az `ADMIN_ADD_USER_MANUAL` változót biztonságos értékkel

### 2. Admin Felhasználó Létrehozása

1. Navigálj a `/backend/web/index.php?r=site%2Fadmin-register` URL-re
2. Töltsd ki a form mezőket:
   - **Felhasználónév**: WordPress formátum (csak betűk, számok, .-_)
   - **E-mail cím**: Érvényes email cím
   - **Jelszó**: Legalább 6 karakter
   - **Jelszó megerősítése**: Ugyanaz mint a jelszó
   - **Admin kulcs**: Az .env fájlban beállított értéke

3. Sikeres regisztráció után átirányítás a bejelentkezési oldalra

### 3. Bejelentkezés

- A bejelentkezés most email cím és jelszó alapú
- A létrehozott admin felhasználó azonnal aktív lesz

## Biztonsági Megfontolások

1. **Environment Változó**: Az admin kulcs soha ne kerüljön verziókezelőbe
2. **Felhasználónév Validáció**: Megakadályozza a rossz formátumú felhasználónevek létrehozását
3. **Email Validáció**: Csak érvényes email címek fogadhatók el
4. **Hozzáférés Kontroll**: A regisztrációs oldal csak az admin kulccsal érhető el

## Fejlesztői Megjegyzések

- A rendszer Tabler.io design-t használ
- Minden hibaüzenet magyar nyelven
- A form validáció kliens és szerver oldalon is történik
- Az admin felhasználók azonnal aktív státuszt kapnak (STATUS_ACTIVE) 