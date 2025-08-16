# Média kezelő modul dokumentáció

**Fejlesztő:** Web Solutions Hungary Kft.  
**Dátum:** 2025. augusztus 16.  
**Modul:** Média kezelése

## Áttekintés

A média kezelő modul lehetővé teszi képek és videók feltöltését, kezelését a backend adminisztrációs felületen. A modul drag & drop funkcionalitással rendelkezik és teljes CRUD műveleteket támogat.

## Funkciók

### Alapfunkciók
- ✅ Drag & drop fájlfeltöltés
- ✅ Hagyományos fájlfeltöltés
- ✅ Képek és videók támogatása
- ✅ Előnézet funkció
- ✅ AJAX alapú feltöltés
- ✅ Tömeges törlés
- ✅ Jogosultságkezelés
- ✅ Fájlméret emberi formátumban
- ✅ Média típus automatikus felismerés

### Támogatott fájlformátumok
- **Képek:** JPG, JPEG, PNG, GIF, WebP
- **Videók:** MP4, AVI, MOV, WMV
- **Egyéb:** PDF, DOC, DOCX

## Technikai implementáció

### Adatbázis struktúra

**Tábla:** `media`

| Mező | Típus | Leírás |
|------|-------|--------|
| id | int(11) | Elsődleges kulcs |
| filename | varchar(255) | Generált fájlnév |
| original_name | varchar(255) | Eredeti fájlnév |
| mime_type | varchar(255) | MIME típus |
| file_path | varchar(255) | Relatív fájl útvonal |
| file_size | int(11) | Fájlméret bájtban |
| media_type | varchar(50) | Média típus (image, video, audio, document, other) |
| alt_text | text | Alt szöveg (SEO) |
| description | text | Leírás |
| width | int(11) | Szélesség (képek/videók) |
| height | int(11) | Magasság (képek/videók) |
| duration | int(11) | Időtartam másodpercben (videók/hangok) |
| status | tinyint(1) | Állapot (0=inaktív, 1=aktív) |
| created_at | int(11) | Létrehozás időbélyege |
| updated_at | int(11) | Módosítás időbélyege |

### Jogosultságok

A következő jogosultságok kerültek létrehozásra:

- `media_view` - Médiák megtekintése
- `media_create` - Médiák feltöltése
- `media_update` - Médiák szerkesztése
- `media_delete` - Médiák törlése

Ezek alapértelmezetten az `admin` szerepkörhöz vannak rendelve.

### Fájlok struktúrája

```
common/models/
├── Media.php                    # Média modell

backend/controllers/
├── MediaController.php          # Média controller

backend/views/media/
├── index.php                    # Lista oldal (drag & drop)
├── create.php                   # Feltöltés oldal
├── view.php                     # Részletes nézet
└── update.php                   # Szerkesztés oldal

backend/web/uploads/media/       # Feltöltött fájlok mappája

console/migrations/
├── m250816_173304_create_media_table.php           # Média tábla létrehozása
└── m250816_173335_add_media_permissions.php        # Jogosultságok hozzáadása
```

### URL útvonalak

```php
'media' => 'media/index',
'media/feltoltes' => 'media/create',
'media/<id:\d+>' => 'media/view',
'media/<id:\d+>/szerkesztes' => 'media/update',
'media/<id:\d+>/torles' => 'media/delete',
'media/tomeges-torles' => 'media/bulk-delete',
'media/ajax-feltoltes' => 'media/ajax-upload',
```

## Használat

### Drag & Drop feltöltés

1. Navigálj a `http://kozter-admin.test/media` oldalra
2. Húzd a fájlokat a feltöltési területre
3. A fájlok automatikusan feltöltődnek AJAX-szal
4. A lista frissül az új médiával

### Hagyományos feltöltés

1. Kattints az "Új média feltöltése" gombra
2. Válaszd ki a fájlt
3. Töltsd ki az opcionális mezőket (alt szöveg, leírás)
4. Kattints a "Feltöltés" gombra

### Média szerkesztése

1. Kattints a média nevére vagy a "Szerkesztés" gombra
2. Módosítsd az alt szöveget, leírást vagy állapotot
3. Mentsd a változtatásokat

### Tömeges törlés

1. Jelöld be a törölni kívánt médiákat
2. Kattints a "Kiválasztottak törlése" gombra
3. Erősítsd meg a törlést

## Biztonsági megfontolások

- ✅ Fájltípus validáció
- ✅ Fájlméret korlátozás
- ✅ CSRF védelem
- ✅ Jogosultságalapú hozzáférés
- ✅ Egyedi fájlnevek generálása

## Testreszabási lehetőségek

### Új fájltípus hozzáadása

```php
// Media modellben
public function rules()
{
    return [
        // ...
        [['uploadedFile'], 'file', 'extensions' => 'jpg, jpeg, png, gif, webp, mp4, avi, mov, wmv, pdf, doc, docx, új_típus'],
        // ...
    ];
}
```

### Fájlméret korlátozás módosítása

```php
// Media modellben
[['uploadedFile'], 'file', 'maxSize' => 52428800] // 50MB
```

### Új média típus hozzáadása

```php
// Media modellben
const TYPE_NEW = 'new_type';

public static function getMediaTypeOptions()
{
    return [
        // ...
        self::TYPE_NEW => 'Új típus',
    ];
}
```

## Hibaelhárítás

### Feltöltési problémák

1. **Fájl túl nagy:** Ellenőrizd a PHP `upload_max_filesize` és `post_max_size` beállításait
2. **Jogosultság hiány:** Biztosítsd, hogy a felhasználónak legyen `media_create` jogosultsága
3. **Mappa jogosultság:** Győződj meg róla, hogy a `backend/web/uploads/media/` mappa írható

### AJAX hibák

1. Ellenőrizd a böngésző konzolt hibákért
2. Győződj meg róla, hogy a CSRF token megfelelően be van állítva
3. Ellenőrizd a szerver hibanaplókat

## Jövőbeli fejlesztési lehetőségek

- [ ] Képszerkesztő integráció
- [ ] Automatikus thumbnail generálás
- [ ] Bulk upload több fájlhoz
- [ ] Média galéria widget
- [ ] Cloud storage integráció (AWS S3, Google Cloud)
- [ ] Képoptimalizálás (WebP konverzió)
- [ ] Keresés és szűrés a médiák között
- [ ] Metadata EXIF információk kinyerése

## Verzióinformációk

**v1.0.0** (2025.08.16)
- Alapfunkciók implementálása
- Drag & drop feltöltés
- CRUD műveletek
- Jogosultságkezelés
- Responsive design

---

*Dokumentáció készítve: Web Solutions Hungary Kft.*
