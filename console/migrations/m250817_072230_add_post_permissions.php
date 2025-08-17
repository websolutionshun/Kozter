<?php

use yii\db\Migration;

class m250817_072230_add_post_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Bejegyzések kezelési jogosultságok hozzáadása
        $this->insert('{{%permissions}}', [
            'name' => 'post_view',
            'description' => 'Bejegyzések megtekintése',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%permissions}}', [
            'name' => 'post_create',
            'description' => 'Bejegyzések létrehozása',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%permissions}}', [
            'name' => 'post_edit',
            'description' => 'Bejegyzések szerkesztése',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%permissions}}', [
            'name' => 'post_delete',
            'description' => 'Bejegyzések törlése',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        // Admin szerepkör jogosultságainak hozzáadása
        $adminRoleId = $this->db->createCommand("SELECT id FROM {{%roles}} WHERE name = 'admin'")->queryScalar();
        
        if ($adminRoleId) {
            $permissions = ['post_view', 'post_create', 'post_edit', 'post_delete'];
            
            foreach ($permissions as $permissionName) {
                $permissionId = $this->db->createCommand("SELECT id FROM {{%permissions}} WHERE name = :name", [':name' => $permissionName])->queryScalar();
                
                if ($permissionId) {
                    $this->insert('{{%role_permissions}}', [
                        'role_id' => $adminRoleId,
                        'permission_id' => $permissionId,
                        'created_at' => time(),
                    ]);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Admin szerepkör jogosultságainak törlése
        $adminRoleId = $this->db->createCommand("SELECT id FROM {{%roles}} WHERE name = 'admin'")->queryScalar();
        
        if ($adminRoleId) {
            $permissions = ['post_view', 'post_create', 'post_edit', 'post_delete'];
            
            foreach ($permissions as $permissionName) {
                $permissionId = $this->db->createCommand("SELECT id FROM {{%permissions}} WHERE name = :name", [':name' => $permissionName])->queryScalar();
                
                if ($permissionId) {
                    $this->delete('{{%role_permissions}}', [
                        'role_id' => $adminRoleId,
                        'permission_id' => $permissionId,
                    ]);
                }
            }
        }

        // Jogosultságok törlése
        $this->delete('{{%permissions}}', ['name' => 'post_view']);
        $this->delete('{{%permissions}}', ['name' => 'post_create']);
        $this->delete('{{%permissions}}', ['name' => 'post_edit']);
        $this->delete('{{%permissions}}', ['name' => 'post_delete']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250817_072230_add_post_permissions cannot be reverted.\n";

        return false;
    }
    */
}
