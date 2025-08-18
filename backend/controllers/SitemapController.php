<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\console\Application;
use yii\console\Request;
use console\models\sitemap\SitemapBlog;
use console\models\sitemap\SitemapOldalak;
use common\models\Post;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * SitemapController kezeli a sitemap admin felületet
 */
class SitemapController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'generate', 'view'],
                        'allow' => true,
                        'roles' => ['@'], // csak bejelentkezett felhasználók
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'generate' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Jogosultság ellenőrzése
     */
    private function checkAccess($action)
    {
        $user = Yii::$app->user->identity;
        
        if (!$user) {
            return false;
        }
        
        if ($action === 'index' || $action === 'view') {
            return $user->hasPermission('sitemap_view');
        }
        
        if ($action === 'generate') {
            return $user->hasPermission('sitemap_generate');
        }
        
        return false;
    }

    /**
     * Sitemap főoldal
     */
    public function actionIndex()
    {
        // Debug: jogosultság ellenőrzés
        $user = Yii::$app->user->identity;
        if (!$user) {
            throw new \yii\web\ForbiddenHttpException('Nincs bejelentkezett felhasználó.');
        }
        
        if (!$user->hasPermission('sitemap_view')) {
            throw new \yii\web\ForbiddenHttpException('Nincs jogosultsága a sitemap megtekintéséhez.');
        }
        
        $sitemapPath = Yii::getAlias('@frontend/web/sitemap.xml');
        $sitemapExists = file_exists($sitemapPath);
        $sitemapStats = null;
        $lastModified = null;

        if ($sitemapExists) {
            $lastModified = filemtime($sitemapPath);
            $sitemapStats = $this->getSitemapStats();
        }

        $blogPostsCount = Post::getPublished()->count();
        $totalPossibleUrls = $blogPostsCount + count($this->getStaticPages());

        return $this->render('index', [
            'sitemapExists' => $sitemapExists,
            'sitemapStats' => $sitemapStats,
            'lastModified' => $lastModified,
            'blogPostsCount' => $blogPostsCount,
            'totalPossibleUrls' => $totalPossibleUrls,
        ]);
    }

    /**
     * Sitemap tartalom megtekintése
     */
    public function actionView()
    {
        $sitemapPath = Yii::getAlias('@frontend/web/sitemap.xml');
        
        if (!file_exists($sitemapPath)) {
            Yii::$app->session->addFlash('error', 'A sitemap.xml fájl nem található. Először generáld le!');
            return $this->redirect(['index']);
        }

        $sitemapContent = file_get_contents($sitemapPath);
        $urls = $this->parseSitemapUrls($sitemapContent);

        return $this->render('view', [
            'urls' => $urls,
            'sitemapPath' => $sitemapPath,
            'fileSize' => filesize($sitemapPath),
            'lastModified' => filemtime($sitemapPath),
        ]);
    }

    /**
     * Sitemap újragenerálása
     */
    public function actionGenerate()
    {
        $oldApp = Yii::$app;
        
        try {
            // Console alkalmazás inicializálása a sitemap generáláshoz
            $config = ArrayHelper::merge(
                require Yii::getAlias('@common/config/main.php'),
                require Yii::getAlias('@console/config/main.php'),
                require Yii::getAlias('@common/config/main-local.php'),
                require Yii::getAlias('@console/config/main-local.php')
            );
            $config['id'] = 'console-app';
            
            new Application($config);
            
            // Sitemap generálás futtatása
            $controller = new \demi\sitemap\SitemapController('sitemap', Yii::$app);
            $controller->modelsPath = '@console/models/sitemap';
            $controller->modelsNamespace = 'console\\models\\sitemap';
            $controller->savePathAlias = '@frontend/web';
            $controller->sitemapFileName = 'sitemap.xml';
            
            ob_start();
            $controller->actionIndex();
            $output = ob_get_clean();
            
            // Eredeti alkalmazás visszaállítása
            Yii::$app = $oldApp;
            
            // Ellenőrizzük, hogy létrejött-e a sitemap fájl
            $sitemapPath = Yii::getAlias('@frontend/web/sitemap.xml');
            if (file_exists($sitemapPath)) {
                Yii::$app->session->addFlash('success', 'A sitemap sikeresen újragenerálva!');
            } else {
                Yii::$app->session->addFlash('error', 'Hiba történt a sitemap generálása során.');
            }
            
        } catch (\Exception $e) {
            // Eredetit visszaállítás kivétel esetén is
            Yii::$app = $oldApp;
            
            Yii::error('Sitemap generálási hiba: ' . $e->getMessage());
            Yii::$app->session->addFlash('error', 'Hiba történt a sitemap generálása során: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Sitemap statisztikák lekérése
     */
    private function getSitemapStats()
    {
        $sitemapPath = Yii::getAlias('@frontend/web/sitemap.xml');
        
        if (!file_exists($sitemapPath)) {
            return null;
        }

        $content = file_get_contents($sitemapPath);
        $urls = $this->parseSitemapUrls($content);
        
        $stats = [
            'total' => count($urls),
            'blog' => 0,
            'static' => 0,
            'other' => 0,
        ];

        foreach ($urls as $url) {
            if (strpos($url['loc'], '/blog') !== false) {
                $stats['blog']++;
            } elseif (in_array($url['loc'], array_map(function($page) {
                return rtrim(Yii::$app->params['frontendUrl'], '/') . $page['url'];
            }, $this->getStaticPages()))) {
                $stats['static']++;
            } else {
                $stats['other']++;
            }
        }

        return $stats;
    }

    /**
     * Sitemap URL-ek kinyerése XML-ből
     */
    private function parseSitemapUrls($xmlContent)
    {
        $urls = [];
        
        try {
            $xml = simplexml_load_string($xmlContent);
            
            if ($xml !== false) {
                foreach ($xml->url as $urlNode) {
                    $urls[] = [
                        'loc' => (string)$urlNode->loc,
                        'lastmod' => (string)$urlNode->lastmod,
                        'changefreq' => (string)$urlNode->changefreq,
                        'priority' => (string)$urlNode->priority,
                    ];
                }
            }
        } catch (\Exception $e) {
            Yii::error('Sitemap parsing hiba: ' . $e->getMessage());
        }

        return $urls;
    }

    /**
     * Statikus oldalak listája
     */
    private function getStaticPages()
    {
        return [
            ['name' => 'Főoldal', 'url' => '/'],
            ['name' => 'Kapcsolat', 'url' => '/kapcsolat'],
            ['name' => 'Bejelentkezés', 'url' => '/bejelentkezes'], 
            ['name' => 'Regisztráció', 'url' => '/regisztracio'],
            ['name' => 'Elfelejtett jelszó', 'url' => '/elfelejtett-jelszo'],
            ['name' => 'Adatkezelési tájékoztató', 'url' => '/adatkezelesi-tajekoztato'],
            ['name' => 'ÁSZF', 'url' => '/aszf'],
            ['name' => 'Rólunk', 'url' => '/rolunk'],
            ['name' => 'Blog főoldal', 'url' => '/blog'],
        ];
    }
}
