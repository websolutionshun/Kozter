<?php

use yii\db\Migration;

class m250813_094529_add_category_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $time = time();
        $this->batchInsert('{{%permissions}}', ['name', 'description', 'category', 'created_at', 'updated_at'], [
            // Kategóriakezelés
            ['category_view', 'Kategóriák megtekintése', 'Kategóriakezelés', $time, $time],
            ['category_create', 'Új kategória létrehozása', 'Kategóriakezelés', $time, $time],
            ['category_edit', 'Kategóriák szerkesztése', 'Kategóriakezelés', $time, $time],
            ['category_delete', 'Kategóriák törlése', 'Kategóriakezelés', $time, $time],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250813_094529_add_category_permissions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250813_094529_add_category_permissions cannot be reverted.\n";

        return false;
    }
    */
}
