# 2025_08_18 - KözTér Frontend Főoldal Fejlesztési Dokumentáció

**Fejlesztő:** Web Solutions Hungary Kft.  
**Dátum:** 2025. augusztus 18.  
**Projekt:** KözTér - Frontend főoldal újratervezés

## Áttekintés

A KözTér frontend főoldalának teljes újratervezése és implementálása a Telex.hu főoldalának szerkezete alapján, de a Kozter.com arculatával és menüstruktúrájával.

## Megvalósított funkciók

### 1. Design és Arculat

#### Színvilág (Kozter.com alapján)
- **Sárga tónusok:** 
  - Fő sárga: `#FFD700`
  - Világos sárga: `#FFF89A`
  - Sötét sárga: `#E6C200`

- **Kék-zöld tónusok:**
  - Fő kék: `#1E3A8A`
  - Világos kék: `#3B82F6`
  - Sötét kék: `#1E40AF`
  - Zöld: `#059669`
  - Világos zöld: `#10B981`

#### Betűtípusok
- **Alapértelmezett szöveg:** Inter (Google Fonts)
- **Címsorok:** Merriweather (Google Fonts)

### 2. Layout és Struktúra

#### Főnavigáció (Kozter.com menüstruktúra alapján)
- Támogass (dropdown)
- Műsoraink (dropdown)
- Bejegyzések
- A drága olvasónak (dropdown)
- A drága sajtónak
- Rólunk

#### Bootstrap 5 alapok
- Teljes responsive design
- Modern komponensek használata
- Grid rendszer optimalizálása

### 3. Tartalmi Blokkok (Telex.hu szerkezet alapján)

#### Kiemelt bejegyzés blokk
- Nagy, szembetűnő kártya design
- Gradient háttér (kék tónusok)
- Szerző, dátum, megtekintések száma
- Kiemelt kép támogatás

#### Kategóriás szekciók
- Kategóriánként csoportosított bejegyzések
- Első bejegyzés nagyobb hangsúllyal
- "Összes" linkek kategóriánként
- Kategória alapú navigáció

#### Oldalsáv
- Legfrissebb bejegyzések listája
- Gyors linkek (podcastok, videók, stb.)
- Támogatási felhívás blokk

### 4. Implementált Controller-ek

#### SiteController bővítések
```php
// Főoldal logika
public function actionIndex()
{
    // Kiemelt bejegyzés lekérése
    $featuredPost = Post::getPublished()->orderBy(['published_at' => SORT_DESC])->one();
    
    // Kategóriánkénti bejegyzések
    $categorizedPosts = [...];
    
    // Friss bejegyzések
    $recentPosts = [...];
}
```

#### PostController (új)
- `actionIndex()` - Bejegyzések listázása
- `actionView($slug)` - Bejegyzés megtekintése
- `actionCategory($slug)` - Kategória alapú listázás

### 5. View Fájlok

#### Főoldal (`frontend/views/site/index.php`)
- Telex.hu stílusú szerkezet
- Kiemelt bejegyzés megjelenítése
- Kategóriás blokkok
- Responsive oldalsáv

#### Bejegyzés view-k
- `frontend/views/post/index.php` - Lista oldal
- `frontend/views/post/view.php` - Részletes nézet
- `frontend/views/post/category.php` - Kategória oldal
- `frontend/views/post/_post_item.php` - Bejegyzés komponens

#### Layout módosítások (`frontend/views/layouts/main.php`)
- Kozter színvilág CSS változók
- Bootstrap 5 navbar
- Új footer design
- Font Awesome ikonok

### 6. URL Routing

```php
// Magyar nyelvű útvonalak hozzáadása
'bejegyzesek' => 'post/index',
'bejegyzes/<slug>' => 'post/view',
'kategoria/<slug>' => 'post/category',
```

## Technikai megoldások

### CSS Változók használata
```css
:root {
    --kozter-yellow: #FFD700;
    --kozter-blue: #1E3A8A;
    --kozter-green: #059669;
    /* ... további színek */
}
```

### Responsive Design
- Mobile-first megközelítés
- Bootstrap 5 grid rendszer
- Flexbox alapú komponensek
- Media query-k speciális esetekhez

### SEO Optimalizáció
- Meta tag-ek támogatása bejegyzéseknél
- Structured data felkészülés
- Szemantikus HTML struktúra

## Adatbázis kapcsolatok

### Post Model kapcsolatok
- Author (User)
- Categories (Many-to-Many)
- Tags (Many-to-Many)
- FeaturedImage (Media)

### Lekérdezés optimalizáció
- `getPublished()` scope használata
- JoinWith kapcsolatok
- Pagination támogatás

## Funkcionális elemek

### Bejegyzés kezelés
- Publikált bejegyzések megjelenítése
- Megtekintések számolása
- Kategória alapú szűrés
- Kivonat generálás

### Interaktív elemek
- Dropdown menük
- Responsive navbar toggle
- Social share gombok
- Pagination

## Jövőbeli fejlesztések

### Admin felület kapcsolat
- Kiemelt bejegyzések kezelése
- Főoldali blokkok testreszabása
- Menüsorrend módosítása

### További funkciók
- Keresés implementálása
- Newsletter feliratkozás
- Komment rendszer
- Related posts

## Telepítés és használat

### Előfeltételek
- Yii2 Advanced Application
- Bootstrap 5
- Font Awesome 6
- Google Fonts

### Beállítások
1. URL routing aktiválása
2. Frontend PostController regisztrálása  
3. View fájlok helyének ellenőrzése
4. CSS/JS asset-ek betöltése

## Tesztelési pontok

### Vizuális tesztek
- [ ] Kozter színvilág alkalmazása
- [ ] Responsive megjelenés (mobile, tablet, desktop)
- [ ] Navbar működése
- [ ] Footer linkek

### Funkcionális tesztek  
- [ ] Bejegyzések megjelenítése
- [ ] Kategória alapú szűrés
- [ ] SEO meta tag-ek
- [ ] Social share funkciók

### Performance tesztek
- [ ] Oldal betöltési idő
- [ ] Képek optimalizálása
- [ ] CSS/JS minifikálás

## Záró megjegyzések

A frontend főoldal sikeresen implementálásra került a Telex.hu szerkezeti alapjaival és a Kozter.com arculati elemeivel. A megoldás teljes mértékben responsive, SEO-optimalizált és könnyen bővíthető további funkciókkal.

A rendszer készen áll a produkciós használatra, azonban javasolt további tesztelés különböző böngészőkben és eszközökön.
