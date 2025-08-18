# Sitemap Generator Modul Dokumentáció

**Létrehozva:** 2025.08.18  
**Fejlesztő:** Web Solutions Hungary Kft.  
**Verzió:** 1.1  
**Utolsó frissítés:** 2025.08.18  

## 📋 Áttekintés

A Közter projektben implementált sitemap generator modul a `demi/sitemap-generator` csomag segítségével automatikusan generálja a webhely sitemap.xml fájlját. A modul három fő területet fed le: blog bejegyzések (Post model), médiatartalmak és statikus oldalak.

## 🔧 Technikai Implementáció

### Composer Függőség
```json
"demi/sitemap-generator": "^1.2"
```

### Console Konfiguráció
**Fájl:** `console/config/main.php`
```php
'controllerMap' => [
    'sitemap' => [
        'class' => 'demi\sitemap\SitemapController',
        'modelsPath' => '@console/models/sitemap',
        'modelsNamespace' => 'console\models\sitemap', 
        'savePathAlias' => '@frontend/web',
        'sitemapFileName' => 'sitemap.xml',
    ],
]
```

## 📁 Sitemap Modellek

### 1. SitemapBlog.php
**Helye:** `console/models/sitemap/SitemapBlog.php`

**Funkció:** Blog bejegyzések és blog főoldal sitemap bejegyzéseinek kezelése

**Főbb jellemzők:**
- A `Post` modellt örökli és a `demi\sitemap\interfaces\Basic` interface-t implementálja
- Batch méret: 10 elem
- Nyelv támogatás: magyar (hu)
- Prioritás: 0.7 (bejegyzések), 0.8 (blog főoldal)
- Változtatási gyakoriság: heti (bejegyzések), napi (főoldal)
- Csak publikált és nyilvános bejegyzéseket tartalmazza

**Implementált metódusok:**
```php
public function getSitemapItems($lang = null)        // Statikus blog oldalak (blog főoldal)
public function getSitemapItemsQuery($lang = null)   // Publikált blog bejegyzések lekérdezése  
public function getSitemapLoc($lang = null)          // URL: /blog/{slug}
public function getSitemapLastmod($lang = null)      // updated_at vagy published_at alapján
public function getSitemapChangefreq($lang = null)   // CHANGEFREQ_WEEKLY
public function getSitemapPriority($lang = null)     // PRIORITY_7
```

**Lekérdezés jellemzői:**
```php
return static::find()
    ->select(['id', 'title', 'slug', 'published_at', 'updated_at'])
    ->where([
        'status' => static::STATUS_PUBLISHED,
        'visibility' => static::VISIBILITY_PUBLIC
    ])
    ->andWhere(['<=', 'published_at', time()])
    ->orderBy(['published_at' => SORT_DESC]);
```

### 2. SitemapOldalak.php
**Helye:** `console/models/sitemap/SitemapOldalak.php`

**Funkció:** Statikus oldalak sitemap bejegyzéseinek kezelése

**Főbb jellemzők:**
- Csak a `Basic` interface-t implementálja (nem örökli modellt)
- 12 statikus oldal definiálva
- Prioritás: 1.0 (főoldal), 0.8 (többi oldal)
- Változtatási gyakoriság: napi

**Tartalmazott oldalak:**
- Főoldal (/)
- Kapcsolat (/kapcsolat)
- Bejelentkezés (/bejelentkezes)
- Regisztráció (/regisztracio)
- Elfelejtett jelszó (/elfelejtett-jelszo)
- Adatkezelési tájékoztató (/adatkezelesi-tajekoztato)
- ÁSZF (/aszf)
- Rólunk (/rolunk)
- Blog főoldal (/blog)

## 🌐 Környezeti Változók

A sitemap modellek a `Yii::$app->params['frontendUrl']` változót használják az URL-ek generálásához.

**Beállítás:** `common/config/params.php`
```php
'frontendUrl' => env('FRONTEND_URL', 'http://kozter.test'),
```

**Fejlesztői környezetben:** `http://kozter.test`
**Éles környezetben:** A megfelelő domain beállítás a `.env` fájlban

## ⚡ Futtatás

### Manuális Generálás
```bash
php yii sitemap --interactive=0
```

### Automatikus Futtatás (Cron)
**Ütemezés:** Naponta vagy szükség szerint
```bash
cd /path/to/kozter && php yii sitemap --interactive=0
```

### Admin Felületről
Az admin felületen keresztül is elérhető a sitemap generálás és monitoring funkció.

## 📄 Kimeneti Fájl

**Helye:** `frontend/web/sitemap.xml`

**Struktúra példa:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>http://kozter.test/</loc>
        <lastmod>2025-08-18T15:27:37+02:00</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>http://kozter.test/blog</loc>
        <lastmod>2025-08-18T15:54:04+02:00</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>http://kozter.test/blog/pelda-bejegyzes</loc>
        <lastmod>2025-08-18T12:50:49+02:00</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <!-- további URL-ek... -->
</urlset>
```

## 🔄 Másik Projektbe Történő Átvitel

### Szükséges Fájlok
1. **Composer függőség hozzáadása**
2. **Console konfiguráció** (`console/config/main.php`)
3. **Teljes sitemap modellek mappa** (`console/models/sitemap/`)
4. **Környezeti változó beállítása**

### Ellenőrzendő Dolgok Új Projektben
- [ ] `Post` modell létezik és megfelelő struktúrával rendelkezik
- [ ] `Yii::$app->params['frontendUrl']` változó be van állítva
- [ ] URL routing struktúra egyezik (`/blog/{slug}`)
- [ ] Interface konstansok elérhetők (`CHANGEFREQ_*`, `PRIORITY_*`)
- [ ] Post modell konstansok elérhetők (`STATUS_PUBLISHED`, `VISIBILITY_PUBLIC`)

### Testreszabási Lehetőségek
- Batch méret módosítása (`$sitemapBatchSize`)
- Prioritások finomhangolása
- Változtatási gyakoriság beállítása
- További statikus oldalak hozzáadása
- Többnyelvű támogatás aktiválása

## 🚀 SEO Előnyök

- **Automatikus indexelés:** Keresőmotorok gyorsabban találják meg az új tartalmakat
- **Priorizálás:** Fontos oldalak magasabb prioritást kapnak
- **Frissítési info:** `lastmod` segíti a keresőmotorokat a változások követésében
- **Teljes lefedettség:** Minden fontos oldal szerepel a sitemap-ben

## 🐛 Hibaelhárítás

### Gyakori Problémák
1. **Üres sitemap:** Ellenőrizze a `$_ENV['frontendUrl']` beállítást
2. **Hibás URL-ek:** Győződjön meg róla, hogy a routing megfelelően van beállítva
3. **Hiányzó termékek:** Ellenőrizze a `termekek_kepek` tábla kapcsolatot

### Debug Mód
```bash
php yii sitemap/debug --interactive=0
```

## 📈 Teljesítmény

- **Batch feldolgozás:** 10 elemes kötegekben dolgozza fel az adatokat
- **Optimalizált lekérdezések:** Csak szükséges mezők kerülnek kiválasztásra
- **Memória hatékonyság:** Nagy adatmennyiség esetén is stabil működés

## 🖥️ Admin Felület

### Sitemap Kezelő
**Hely:** Backend admin felület `/sitemap` útvonal

**Funkciók:**
- **Sitemap áttekintés:** Az aktuális sitemap tartalmának megjelenítése
- **Statisztikák:** URL-ek száma típusonként (statikus oldalak, blog bejegyzések, stb.)
- **Utolsó generálás:** Időbélyeg és státusz információ
- **Manuális generálás:** "Sitemap újragenerálása" gomb
- **Jogosultság alapú hozzáférés:** Csak megfelelő jogosultsággal rendelkező adminok férhetnek hozzá

### Jogosultságok
- `sitemap_view`: Sitemap tartalom megtekintése
- `sitemap_generate`: Sitemap újragenerálása

### Használat
1. Admin bejelentkezés után navigálj a "Rendszer" > "Sitemap" menüpontra
2. Tekintsd meg az aktuális sitemap státuszt és tartalmát
3. Szükség esetén kattints a "Sitemap újragenerálása" gombra
4. A folyamat után frissül a sitemap.xml fájl

---

**Utoljára frissítve:** 2025.08.18  
**Státusz:** Aktív, éles környezetben működik  
**Admin felület:** Implementálva
