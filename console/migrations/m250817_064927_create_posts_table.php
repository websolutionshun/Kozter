<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%posts}}`.
 */
class m250817_064927_create_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%posts}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull()->comment('Bejegyzés címe'),
            'slug' => $this->string(255)->notNull()->unique()->comment('URL-barát cím'),
            'content' => $this->text()->comment('Bejegyzés tartalma'),
            'excerpt' => $this->text()->comment('Rövid kivonat'),
            'status' => $this->tinyInteger(1)->defaultValue(0)->comment('0=vázlat, 1=publikált, 2=privát'),
            'visibility' => $this->tinyInteger(1)->defaultValue(1)->comment('1=nyilvános, 2=jelszóval védett, 3=privát'),
            'password' => $this->string(255)->comment('Jelszó védett bejegyzésekhez'),
            'featured_image_id' => $this->integer()->comment('Kiemelt kép média ID'),
            'author_id' => $this->integer()->notNull()->comment('Szerző felhasználó ID'),
            'published_at' => $this->integer()->comment('Publikálás dátuma (timestamp)'),
            'seo_title' => $this->string(255)->comment('SEO cím'),
            'seo_description' => $this->text()->comment('SEO leírás'),
            'seo_keywords' => $this->string(500)->comment('SEO kulcsszavak'),
            'seo_canonical_url' => $this->string(255)->comment('SEO canonical URL'),
            'seo_robots' => $this->string(100)->defaultValue('index,follow')->comment('SEO robots meta'),
            'view_count' => $this->integer()->defaultValue(0)->comment('Megtekintések száma'),
            'comment_status' => $this->tinyInteger(1)->defaultValue(1)->comment('0=letiltva, 1=engedélyezve'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        // Indexek hozzáadása
        $this->createIndex('idx-posts-slug', '{{%posts}}', 'slug');
        $this->createIndex('idx-posts-status', '{{%posts}}', 'status');
        $this->createIndex('idx-posts-author_id', '{{%posts}}', 'author_id');
        $this->createIndex('idx-posts-published_at', '{{%posts}}', 'published_at');
        $this->createIndex('idx-posts-featured_image_id', '{{%posts}}', 'featured_image_id');

        // Foreign key kapcsolatok
        $this->addForeignKey(
            'fk-posts-author_id',
            '{{%posts}}',
            'author_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // Media tábla ellenőrzése és foreign key hozzáadása, ha létezik
        $tableSchema = $this->db->getTableSchema('{{%media}}');
        if ($tableSchema !== null) {
            $this->addForeignKey(
                'fk-posts-featured_image_id',
                '{{%posts}}',
                'featured_image_id',
                '{{%media}}',
                'id',
                'SET NULL'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Foreign key-k törlése
        $this->dropForeignKey('fk-posts-author_id', '{{%posts}}');
        $this->dropForeignKey('fk-posts-featured_image_id', '{{%posts}}');
        
        // Indexek törlése
        $this->dropIndex('idx-posts-slug', '{{%posts}}');
        $this->dropIndex('idx-posts-status', '{{%posts}}');
        $this->dropIndex('idx-posts-author_id', '{{%posts}}');
        $this->dropIndex('idx-posts-published_at', '{{%posts}}');
        $this->dropIndex('idx-posts-featured_image_id', '{{%posts}}');
        
        // Tábla törlése
        $this->dropTable('{{%posts}}');
    }
}
