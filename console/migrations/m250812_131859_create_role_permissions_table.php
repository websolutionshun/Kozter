<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%role_permissions}}`.
 */
class m250812_131859_create_role_permissions_table extends Migration
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

        $this->createTable('{{%role_permissions}}', [
            'id' => $this->primaryKey(),
            'role_id' => $this->integer()->notNull(),
            'permission_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        // Foreign key constraints
        $this->addForeignKey(
            'fk-role_permissions-role_id',
            '{{%role_permissions}}',
            'role_id',
            '{{%roles}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-role_permissions-permission_id',
            '{{%role_permissions}}',
            'permission_id',
            '{{%permissions}}',
            'id',
            'CASCADE'
        );

        // Unique constraint
        $this->createIndex(
            'idx-role_permissions-unique',
            '{{%role_permissions}}',
            ['role_id', 'permission_id'],
            true
        );

        // Alapértelmezett admin jogosultságok hozzáadása (admin szerepkörnek minden jog)
        $time = time();
        $adminRoleId = 1; // admin szerepkör ID-ja
        
        for ($permissionId = 1; $permissionId <= 12; $permissionId++) {
            $this->insert('{{%role_permissions}}', [
                'role_id' => $adminRoleId,
                'permission_id' => $permissionId,
                'created_at' => $time,
            ]);
        }

        // Szerkesztő alapértelmezett jogosultságok
        $editorRoleId = 2; // szerkesztő szerepkör ID-ja
        $editorPermissions = [1, 2, 3, 5, 9, 12]; // user_view, user_create, user_edit, role_view, permission_view, admin_panel
        
        foreach ($editorPermissions as $permissionId) {
            $this->insert('{{%role_permissions}}', [
                'role_id' => $editorRoleId,
                'permission_id' => $permissionId,
                'created_at' => $time,
            ]);
        }

        // Szerző alapértelmezett jogosultságok
        $authorRoleId = 3; // szerző szerepkör ID-ja
        $authorPermissions = [1, 12]; // user_view, admin_panel
        
        foreach ($authorPermissions as $permissionId) {
            $this->insert('{{%role_permissions}}', [
                'role_id' => $authorRoleId,
                'permission_id' => $permissionId,
                'created_at' => $time,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%role_permissions}}');
    }
}
