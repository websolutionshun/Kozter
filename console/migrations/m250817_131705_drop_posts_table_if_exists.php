<?php

use yii\db\Migration;

class m250817_131705_drop_posts_table_if_exists extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Ellenőrizzük és töröljük a post_categories táblát
        $postCategoriesExists = $this->db->schema->getTableSchema('{{%post_categories}}') !== null;
        if ($postCategoriesExists) {
            try {
                $this->dropForeignKey('fk-post_categories-post_id', '{{%post_categories}}');
                echo "Foreign key 'fk-post_categories-post_id' törölve.\n";
            } catch (Exception $e) {
                echo "Foreign key 'fk-post_categories-post_id' már nem létezik: " . $e->getMessage() . "\n";
            }
            
            try {
                $this->dropTable('{{%post_categories}}');
                echo "post_categories tábla törölve.\n";
            } catch (Exception $e) {
                echo "post_categories tábla törlése sikertelen: " . $e->getMessage() . "\n";
            }
        }
        
        // Ellenőrizzük és töröljük a post_tags táblát
        $postTagsExists = $this->db->schema->getTableSchema('{{%post_tags}}') !== null;
        if ($postTagsExists) {
            try {
                $this->dropForeignKey('fk-post_tags-post_id', '{{%post_tags}}');
                echo "Foreign key 'fk-post_tags-post_id' törölve.\n";
            } catch (Exception $e) {
                echo "Foreign key 'fk-post_tags-post_id' már nem létezik: " . $e->getMessage() . "\n";
            }
            
            try {
                $this->dropTable('{{%post_tags}}');
                echo "post_tags tábla törölve.\n";
            } catch (Exception $e) {
                echo "post_tags tábla törlése sikertelen: " . $e->getMessage() . "\n";
            }
        }
        
        // Most már biztonságosan törölhetjük a posts táblát
        $postsTableExists = $this->db->schema->getTableSchema('{{%posts}}') !== null;
        if ($postsTableExists) {
            try {
                $this->dropTable('{{%posts}}');
                echo "posts tábla törölve.\n";
            } catch (Exception $e) {
                echo "posts tábla törlése sikertelen: " . $e->getMessage() . "\n";
            }
        } else {
            echo "posts tábla már nem létezik.\n";
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250817_131705_drop_posts_table_if_exists cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250817_131705_drop_posts_table_if_exists cannot be reverted.\n";

        return false;
    }
    */
}
