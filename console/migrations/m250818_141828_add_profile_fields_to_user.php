<?php

use yii\db\Migration;

class m250818_141828_add_profile_fields_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'profile_image', $this->string()->null()->comment('Profilkép elérési útja'));
        $this->addColumn('{{%user}}', 'nickname', $this->string(100)->null()->comment('Becenév'));
        $this->addColumn('{{%user}}', 'bio', $this->text()->null()->comment('Bemutatkozás'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'bio');
        $this->dropColumn('{{%user}}', 'nickname');
        $this->dropColumn('{{%user}}', 'profile_image');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250818_141828_add_profile_fields_to_user cannot be reverted.\n";

        return false;
    }
    */
}
