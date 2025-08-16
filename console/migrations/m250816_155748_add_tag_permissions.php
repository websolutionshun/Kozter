<?php

use yii\db\Migration;

class m250816_155748_add_tag_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

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
