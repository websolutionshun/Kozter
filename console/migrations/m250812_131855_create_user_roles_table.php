<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_roles}}`.
 */
class m250812_131855_create_user_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_roles}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'role_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Foreign key constraints
        $this->addForeignKey(
            'fk-user_roles-user_id',
            '{{%user_roles}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_roles-role_id',
            '{{%user_roles}}',
            'role_id',
            '{{%roles}}',
            'id',
            'CASCADE'
        );

        // Unique constraint
        $this->createIndex(
            'idx-user_roles-unique',
            '{{%user_roles}}',
            ['user_id', 'role_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_roles}}');
    }
}
