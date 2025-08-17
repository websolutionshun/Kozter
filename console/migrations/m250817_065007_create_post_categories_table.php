<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post_categories}}`.
 */
class m250817_065007_create_post_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post_categories}}', [
            'post_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        // Összetett primary key
        $this->addPrimaryKey('pk-post_categories', '{{%post_categories}}', ['post_id', 'category_id']);

        // Indexek
        $this->createIndex('idx-post_categories-post_id', '{{%post_categories}}', 'post_id');
        $this->createIndex('idx-post_categories-category_id', '{{%post_categories}}', 'category_id');

        // Foreign key kapcsolatok
        $this->addForeignKey(
            'fk-post_categories-post_id',
            '{{%post_categories}}',
            'post_id',
            '{{%posts}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-post_categories-category_id',
            '{{%post_categories}}',
            'category_id',
            '{{%categories}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Foreign key-k törlése
        $this->dropForeignKey('fk-post_categories-post_id', '{{%post_categories}}');
        $this->dropForeignKey('fk-post_categories-category_id', '{{%post_categories}}');
        
        // Indexek törlése
        $this->dropIndex('idx-post_categories-post_id', '{{%post_categories}}');
        $this->dropIndex('idx-post_categories-category_id', '{{%post_categories}}');
        
        // Primary key törlése
        $this->dropPrimaryKey('pk-post_categories', '{{%post_categories}}');
        
        // Tábla törlése
        $this->dropTable('{{%post_categories}}');
    }
}
