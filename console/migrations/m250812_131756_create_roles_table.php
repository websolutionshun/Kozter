<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%roles}}`.
 */
class m250812_131756_create_roles_table extends Migration
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

        $this->createTable('{{%roles}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->unique(),
            'description' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Alapértelmezett szerepkörök beszúrása
        $this->batchInsert('{{%roles}}', ['name', 'description', 'created_at', 'updated_at'], [
            ['admin', 'Teljes rendszeradminisztrátori jogosultságok', time(), time()],
            ['szerkesztő', 'Tartalom szerkesztési jogosultságok', time(), time()],
            ['szerző', 'Alapvető tartalomkészítési jogosultságok', time(), time()],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%roles}}');
    }
}
