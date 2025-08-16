<?php

use yii\db\Migration;

class m250816_155748_add_tag_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $time = time();
        $this->batchInsert('{{%permissions}}', ['name', 'description', 'category', 'created_at', 'updated_at'], [
            // Címkekezelés
            ['tag_view', 'Címkék megtekintése', 'Címkekezelés', $time, $time],
            ['tag_create', 'Új címke létrehozása', 'Címkekezelés', $time, $time],
            ['tag_edit', 'Címkék szerkesztése', 'Címkekezelés', $time, $time],
            ['tag_delete', 'Címkék törlése', 'Címkekezelés', $time, $time],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250816_155748_add_tag_permissions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250816_155748_add_tag_permissions cannot be reverted.\n";

        return false;
    }
    */
}
