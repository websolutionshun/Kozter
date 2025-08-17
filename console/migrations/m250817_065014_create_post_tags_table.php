<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post_tags}}`.
 */
class m250817_065014_create_post_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post_tags}}', [
            'post_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        // Összetett primary key
        $this->addPrimaryKey('pk-post_tags', '{{%post_tags}}', ['post_id', 'tag_id']);

        // Indexek
        $this->createIndex('idx-post_tags-post_id', '{{%post_tags}}', 'post_id');
        $this->createIndex('idx-post_tags-tag_id', '{{%post_tags}}', 'tag_id');

        // Foreign key kapcsolatok
        $this->addForeignKey(
            'fk-post_tags-post_id',
            '{{%post_tags}}',
            'post_id',
            '{{%posts}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-post_tags-tag_id',
            '{{%post_tags}}',
            'tag_id',
            '{{%tags}}',
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
        $this->dropForeignKey('fk-post_tags-post_id', '{{%post_tags}}');
        $this->dropForeignKey('fk-post_tags-tag_id', '{{%post_tags}}');
        
        // Indexek törlése
        $this->dropIndex('idx-post_tags-post_id', '{{%post_tags}}');
        $this->dropIndex('idx-post_tags-tag_id', '{{%post_tags}}');
        
        // Primary key törlése
        $this->dropPrimaryKey('pk-post_tags', '{{%post_tags}}');
        
        // Tábla törlése
        $this->dropTable('{{%post_tags}}');
    }
}
