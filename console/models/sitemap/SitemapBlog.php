<?php

namespace console\models\sitemap;

use Yii;
use yii\helpers\Url;
use common\models\Post;
use demi\sitemap\interfaces\Basic;
use demi\sitemap\interfaces\GoogleAlternateLang;
use demi\sitemap\interfaces\GoogleImage;

class SitemapBlog extends Post implements Basic
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
        // Add to sitemap.xml links to regular blog pages
        return [
            // Blog főoldal hozzáadása a sitemap-hez
            [
                'loc' => rtrim(Yii::$app->params['frontendUrl'], '/') . '/blog',
                'lastmod' => time(),
                'changefreq' => static::CHANGEFREQ_DAILY,
                'priority' => static::PRIORITY_8,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSitemapItemsQuery($lang = null)
    {
        // Base select query for published posts
        return static::find()
            ->select(['id', 'title', 'slug', 'published_at', 'updated_at'])
            ->where([
                'status' => static::STATUS_PUBLISHED,
                'visibility' => static::VISIBILITY_PUBLIC
            ])
            ->andWhere(['<=', 'published_at', time()])
            ->orderBy(['published_at' => SORT_DESC]);
    }

    /**
     * @inheritdoc
     */
    public function getSitemapLoc($lang = null)
    {
        // Return absolute url to Post model view page
        // A blog bejegyzések elérése /blog/<slug> URL-en keresztül
        return rtrim(Yii::$app->params['frontendUrl'], '/') . '/blog/' . $this->slug;
    }

    /**
     * @inheritdoc
     */
    public function getSitemapLastmod($lang = null)
    {
        // A frissítés dátuma, vagy ha nincs akkor a publikálás dátuma
        return $this->updated_at ?: $this->published_at;
    }

    /**
     * @inheritdoc
     */
    public function getSitemapChangefreq($lang = null)
    {
        return static::CHANGEFREQ_WEEKLY;
    }

    /**
     * @inheritdoc
     */
    public function getSitemapPriority($lang = null)
    {
        return static::PRIORITY_7;
    }

    /* END OF Basic INTERFACE */
}