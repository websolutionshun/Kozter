<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * Post model
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $excerpt
 * @property integer $status
 * @property integer $visibility
 * @property string $password
 * @property integer $featured_image_id
 * @property integer $author_id
 * @property integer $published_at
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property string $seo_canonical_url
 * @property string $seo_robots
 * @property integer $view_count
 * @property integer $comment_status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $author
 * @property Media $featuredImage
 * @property Category[] $categories
 * @property Tag[] $tags
 * @property PostCategory[] $postCategories
 * @property PostTag[] $postTags
 */
class Post extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_PRIVATE = 2;

    const VISIBILITY_PUBLIC = 1;
    const VISIBILITY_PASSWORD = 2;
    const VISIBILITY_PRIVATE = 3;

    const COMMENT_DISABLED = 0;
    const COMMENT_ENABLED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%posts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'immutable' => false,
                'ensureUnique' => true,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'author_id'], 'required', 'message' => '{attribute} megadása kötelező.'],
            [['content', 'excerpt', 'seo_description'], 'string'],
            [['status', 'visibility', 'featured_image_id', 'author_id', 'published_at', 'view_count', 'comment_status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'slug', 'password', 'seo_title', 'seo_canonical_url'], 'string', 'max' => 255],
            [['seo_keywords'], 'string', 'max' => 500],
            [['seo_robots'], 'string', 'max' => 100],
            [['slug'], 'unique', 'message' => 'Ez az URL név már használatban van.'],
            [['status'], 'in', 'range' => [self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_PRIVATE]],
            [['visibility'], 'in', 'range' => [self::VISIBILITY_PUBLIC, self::VISIBILITY_PASSWORD, self::VISIBILITY_PRIVATE]],
            [['comment_status'], 'in', 'range' => [self::COMMENT_DISABLED, self::COMMENT_ENABLED]],
            [['author_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            [['featured_image_id'], 'exist', 'targetClass' => Media::class, 'targetAttribute' => 'id'],
            [['password'], 'required', 'when' => function($model) {
                return $model->visibility == self::VISIBILITY_PASSWORD;
            }, 'message' => 'Jelszó megadása kötelező jelszóval védett bejegyzéseknél.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Cím',
            'slug' => 'Slug',
            'content' => 'Tartalom',
            'excerpt' => 'Kivonat',
            'status' => 'Állapot',
            'visibility' => 'Láthatóság',
            'password' => 'Jelszó',
            'featured_image_id' => 'Kiemelt kép',
            'author_id' => 'Szerző',
            'published_at' => 'Publikálás dátuma',
            'seo_title' => 'SEO cím',
            'seo_description' => 'SEO leírás',
            'seo_keywords' => 'SEO kulcsszavak',
            'seo_canonical_url' => 'SEO canonical URL',
            'seo_robots' => 'SEO robots',
            'view_count' => 'Megtekintések',
            'comment_status' => 'Hozzászólások',
            'created_at' => 'Létrehozva',
            'updated_at' => 'Frissítve',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[FeaturedImage]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFeaturedImage()
    {
        return $this->hasOne(Media::class, ['id' => 'featured_image_id']);
    }

    /**
     * Gets query for [[PostCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostCategories()
    {
        return $this->hasMany(PostCategory::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable('{{%post_categories}}', ['post_id' => 'id']);
    }

    /**
     * Gets query for [[PostTags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostTags()
    {
        return $this->hasMany(PostTag::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[Tags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->viaTable('{{%post_tags}}', ['post_id' => 'id']);
    }

    /**
     * Állapot opciók lekérése
     *
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_DRAFT => 'Vázlat',
            self::STATUS_PUBLISHED => 'Publikált',
            self::STATUS_PRIVATE => 'Privát',
        ];
    }

    /**
     * Láthatóság opciók lekérése
     *
     * @return array
     */
    public static function getVisibilityOptions()
    {
        return [
            self::VISIBILITY_PUBLIC => 'Nyilvános',
            self::VISIBILITY_PASSWORD => 'Jelszóval védett',
            self::VISIBILITY_PRIVATE => 'Privát',
        ];
    }

    /**
     * Hozzászólás állapot opciók lekérése
     *
     * @return array
     */
    public static function getCommentStatusOptions()
    {
        return [
            self::COMMENT_DISABLED => 'Letiltva',
            self::COMMENT_ENABLED => 'Engedélyezve',
        ];
    }

    /**
     * Állapot neve
     *
     * @return string
     */
    public function getStatusName()
    {
        $options = self::getStatusOptions();
        return $options[$this->status] ?? 'Ismeretlen';
    }

    /**
     * Láthatóság neve
     *
     * @return string
     */
    public function getVisibilityName()
    {
        $options = self::getVisibilityOptions();
        return $options[$this->visibility] ?? 'Ismeretlen';
    }

    /**
     * Hozzászólás állapot neve
     *
     * @return string
     */
    public function getCommentStatusName()
    {
        $options = self::getCommentStatusOptions();
        return $options[$this->comment_status] ?? 'Ismeretlen';
    }

    /**
     * Publikált bejegyzések lekérése
     *
     * @return \yii\db\ActiveQuery
     */
    public static function getPublished()
    {
        return self::find()->where([
            'status' => self::STATUS_PUBLISHED,
            'visibility' => self::VISIBILITY_PUBLIC
        ])->andWhere(['<=', 'published_at', time()]);
    }

    /**
     * Rövid tartalom generálása
     *
     * @param int $length
     * @return string
     */
    public function getShortContent($length = 150)
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }
        
        $content = strip_tags($this->content);
        if (mb_strlen($content, 'UTF-8') <= $length) {
            return $content;
        }
        
        return mb_substr($content, 0, $length, 'UTF-8') . '...';
    }

    /**
     * Kategóriák neve vesszővel elválasztva
     *
     * @return string
     */
    public function getCategoriesText()
    {
        $categories = [];
        foreach ($this->categories as $category) {
            $categories[] = $category->name;
        }
        return implode(', ', $categories);
    }

    /**
     * Címkék neve vesszővel elválasztva
     *
     * @return string
     */
    public function getTagsText()
    {
        $tags = [];
        foreach ($this->tags as $tag) {
            $tags[] = $tag->name;
        }
        return implode(', ', $tags);
    }

    /**
     * Publikálás dátumának beállítása mentés előtt
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Ha a bejegyzés publikálásra kerül és nincs publikálási dátum, akkor beállítjuk a jelenlegi időt
        if ($this->status == self::STATUS_PUBLISHED && !$this->published_at) {
            $this->published_at = time();
        }

        return true;
    }

    /**
     * SEO robots alapértelmezett érték
     */
    public function afterFind()
    {
        parent::afterFind();
        
        if (!$this->seo_robots) {
            $this->seo_robots = 'index,follow';
        }
    }
}
