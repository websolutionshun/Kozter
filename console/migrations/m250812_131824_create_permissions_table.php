<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%permissions}}`.
 */
class m250812_131824_create_permissions_table extends Migration
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

        $this->createTable('{{%permissions}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->unique(),
            'description' => $this->text(),
            'category' => $this->string(50),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Alapértelmezett jogosultságok beszúrása
        $time = time();
        $this->batchInsert('{{%permissions}}', ['name', 'description', 'category', 'created_at', 'updated_at'], [
            // Felhasználókezelés
            ['user_view', 'Felhasználók megtekintése', 'Felhasználókezelés', $time, $time],
            ['user_create', 'Új felhasználó létrehozása', 'Felhasználókezelés', $time, $time],
            ['user_edit', 'Felhasználók szerkesztése', 'Felhasználókezelés', $time, $time],
            ['user_delete', 'Felhasználók törlése', 'Felhasználókezelés', $time, $time],
            
            // Szerepkörkezelés
            ['role_view', 'Szerepkörök megtekintése', 'Szerepkörkezelés', $time, $time],
            ['role_create', 'Új szerepkör létrehozása', 'Szerepkörkezelés', $time, $time],
            ['role_edit', 'Szerepkörök szerkesztése', 'Szerepkörkezelés', $time, $time],
            ['role_delete', 'Szerepkörök törlése', 'Szerepkörkezelés', $time, $time],
            
            // Jogosultságkezelés
            ['permission_view', 'Jogosultságok megtekintése', 'Jogosultságkezelés', $time, $time],
            ['permission_manage', 'Jogosultságok kezelése', 'Jogosultságkezelés', $time, $time],
            
            // Rendszerbeállítások
            ['system_settings', 'Rendszerbeállítások kezelése', 'Rendszer', $time, $time],
            ['admin_panel', 'Admin panel elérése', 'Rendszer', $time, $time],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%permissions}}');
    }
}
