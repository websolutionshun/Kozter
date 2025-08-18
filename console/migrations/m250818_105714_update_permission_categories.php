<?php

use yii\db\Migration;

class m250818_105714_update_permission_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Média kategória beállítása
        $this->update('{{%permissions}}', 
            ['category' => 'Médiák'], 
            ['name' => ['media_view', 'media_create', 'media_update', 'media_delete']]
        );

        // Bejegyzések kategória beállítása
        $this->update('{{%permissions}}', 
            ['category' => 'Bejegyzések'], 
            ['name' => ['post_view', 'post_create', 'post_edit', 'post_delete']]
        );

        // Rendszerlogok kategória beállítása
        $this->update('{{%permissions}}', 
            ['category' => 'Rendszerlogok'], 
            ['name' => ['log_view', 'log_manage']]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250818_105714_update_permission_categories cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250818_105714_update_permission_categories cannot be reverted.\n";

        return false;
    }
    */
}
