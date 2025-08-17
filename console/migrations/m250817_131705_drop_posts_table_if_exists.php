<?php

use yii\db\Migration;

class m250817_131705_drop_posts_table_if_exists extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Töröljük a posts táblát, ha létezik
        $this->execute("DROP TABLE IF EXISTS posts");
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
