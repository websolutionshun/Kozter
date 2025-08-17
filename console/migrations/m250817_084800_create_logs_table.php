<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%logs}}`.
 */
class m250817_084800_create_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%logs}}', [
            'id' => $this->primaryKey(),
            'level' => $this->string(20)->notNull()->comment('Log szint (error, warning, info, success)'),
            'category' => $this->string(255)->defaultValue(null)->comment('Log kategória'),
            'message' => $this->text()->notNull()->comment('Log üzenet'),
            'data' => $this->text()->defaultValue(null)->comment('Kiegészítő adatok JSON formátumban'),
            'user_id' => $this->integer()->defaultValue(null)->comment('Felhasználó ID aki a műveletet végezte'),
            'ip_address' => $this->string(45)->defaultValue(null)->comment('IP cím'),
            'user_agent' => $this->text()->defaultValue(null)->comment('User Agent'),
            'url' => $this->string(2048)->defaultValue(null)->comment('Kérés URL'),
            'method' => $this->string(10)->defaultValue(null)->comment('HTTP metódus'),
            'created_at' => $this->integer()->notNull()->comment('Létrehozás időpontja'),
        ]);

        // Indexek létrehozása
        $this->createIndex('idx_logs_level', '{{%logs}}', 'level');
        $this->createIndex('idx_logs_category', '{{%logs}}', 'category');
        $this->createIndex('idx_logs_user_id', '{{%logs}}', 'user_id');
        $this->createIndex('idx_logs_created_at', '{{%logs}}', 'created_at');

        // Foreign key a user táblához
        $this->addForeignKey(
            'fk_logs_user_id',
            '{{%logs}}',
            'user_id',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%logs}}');
    }
}
