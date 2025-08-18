<?php

namespace console\models\sitemap;

use Yii;
use yii\helpers\Url;
use common\models\Mintak;
use demi\sitemap\interfaces\Basic;
use demi\sitemap\interfaces\GoogleAlternateLang;
use demi\sitemap\interfaces\GoogleImage;

class SitemapOldalak implements Basic
{
    /**
     * Handle materials by selecting batch of elements.
     * Increase this value and got more handling speed but more memory usage.
     *
     * @var int
     */
    public $sitemapBatchSize = 10;
    /**
     * List of available site languages
     *
     * @var array [langId => langCode]
     */
    public $sitemapLanguages = [
        'hu',
    ];
    /**
     * If TRUE - Yii::$app->language will be switched for each sitemapLanguages and restored after.
     *
     * @var bool
     */
    public $sitemapSwithLanguages = true;

    /* BEGIN OF Basic INTERFACE */

    /**
     * @inheritdoc
     */
    public function getSitemapItems($lang = null)
    {
        // Add to sitemap.xml links to regular pages
        return [
            ['loc' => $_ENV['FRONTEND_URL'], 'lastmod' => time(), 'changefreq' => static::CHANGEFREQ_DAILY, 'priority' => static::PRIORITY_10],    
            ['loc' => $_ENV['FRONTEND_URL'] . 'kapcsolat', 'lastmod' => time(), 'changefreq' => static::CHANGEFREQ_DAILY, 'priority' => static::PRIORITY_8],
            ['loc' => $_ENV['FRONTEND_URL'] . 'kosar', 'lastmod' => time(), 'changefreq' => static::CHANGEFREQ_DAILY, 'priority' => static::PRIORITY_8],
            ['loc' => $_ENV['FRONTEND_URL'] . 'bejelentkezes', 'lastmod' => time(), 'changefreq' => static::CHANGEFREQ_DAILY, 'priority' => static::PRIORITY_8],
            ['loc' => $_ENV['FRONTEND_URL'] . 'regisztracio', 'lastmod' => time(), 'changefreq' => static::CHANGEFREQ_DAILY, 'priority' => static::PRIORITY_8],
            ['loc' => $_ENV['FRONTEND_URL'] . 'elfelejtett-jelszo', 'lastmod' => time(), 'changefreq' => static::CHANGEFREQ_DAILY, 'priority' => static::PRIORITY_8],
            ['loc' => $_ENV['FRONTEND_URL'] . 'adatkezelesi-tajekoztato', 'lastmod' => time(), 'changefreq' => static::CHANGEFREQ_DAILY, 'priority' => static::PRIORITY_8],
            ['loc' => $_ENV['FRONTEND_URL'] . 'aszf', 'lastmod' => time(), 'changefreq' => static::CHANGEFREQ_DAILY, 'priority' => static::PRIORITY_8],
            ['loc' => $_ENV['FRONTEND_URL'] . 'viszonteladoknak', 'lastmod' => time(), 'changefreq' => static::CHANGEFREQ_DAILY, 'priority' => static::PRIORITY_8],
            ['loc' => $_ENV['FRONTEND_URL'] . 'gyik', 'lastmod' => time(), 'changefreq' => static::CHANGEFREQ_DAILY, 'priority' => static::PRIORITY_8],
            ['loc' => $_ENV['FRONTEND_URL'] . 'rolunk', 'lastmod' => time(), 'changefreq' => static::CHANGEFREQ_DAILY, 'priority' => static::PRIORITY_8],
            ['loc' => $_ENV['FRONTEND_URL'] . 'termekeink', 'lastmod' => time(), 'changefreq' => static::CHANGEFREQ_DAILY, 'priority' => static::PRIORITY_8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSitemapItemsQuery($lang = null)
    {
    }

    /**
     * @inheritdoc
     */
    public function getSitemapLoc($lang = null)
    {
    }

    /**
     * @inheritdoc
     */
    public function getSitemapLastmod($lang = null)
    {
    }

    /**
     * @inheritdoc
     */
    public function getSitemapChangefreq($lang = null)
    {
        return static::CHANGEFREQ_MONTHLY;
    }

    /**
     * @inheritdoc
     */
    public function getSitemapPriority($lang = null)
    {
        return static::PRIORITY_8;
    }

    /* END OF Basic INTERFACE */
}
