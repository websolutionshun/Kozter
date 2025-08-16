<?php

use yii\db\Migration;

class m250816_173335_add_media_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Média jogosultságok hozzáadása
        $this->insert('{{%permissions}}', [
            'name' => 'media_view',
            'description' => 'Médiák megtekintése',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%permissions}}', [
            'name' => 'media_create',
            'description' => 'Médiák feltöltése',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%permissions}}', [
            'name' => 'media_update',
            'description' => 'Médiák szerkesztése',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%permissions}}', [
            'name' => 'media_delete',
            'description' => 'Médiák törlése',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        // Admin szerepkör ID lekérése
        $adminRole = $this->db->createCommand('SELECT id FROM {{%roles}} WHERE name = :name')
            ->bindValue(':name', 'admin')
            ->queryScalar();

        if ($adminRole) {
            // Jogosultságok ID-jének lekérése
            $permissions = [
                'media_view',
                'media_create', 
                'media_update',
                'media_delete'
            ];

            foreach ($permissions as $permissionName) {
                $permissionId = $this->db->createCommand('SELECT id FROM {{%permissions}} WHERE name = :name')
                    ->bindValue(':name', $permissionName)
                    ->queryScalar();

                if ($permissionId) {
                    $this->insert('{{%role_permissions}}', [
                        'role_id' => $adminRole,
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
        // Média jogosultságok törlése
        $permissions = ['media_view', 'media_create', 'media_update', 'media_delete'];
        
        foreach ($permissions as $permissionName) {
            $permissionId = $this->db->createCommand('SELECT id FROM {{%permissions}} WHERE name = :name')
                ->bindValue(':name', $permissionName)
                ->queryScalar();
                
            if ($permissionId) {
                $this->delete('{{%role_permissions}}', ['permission_id' => $permissionId]);
                $this->delete('{{%permissions}}', ['id' => $permissionId]);
            }
        }
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250816_173335_add_media_permissions cannot be reverted.\n";

        return false;
    }
    */
}
