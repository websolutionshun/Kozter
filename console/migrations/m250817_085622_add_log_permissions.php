<?php

use yii\db\Migration;

class m250817_085622_add_log_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Log kezelési jogosultságok hozzáadása
        $permissions = [
            'log_view' => 'Rendszerlogok megtekintése',
            'log_manage' => 'Rendszerlogok kezelése (törlés, statisztikák)',
        ];

        foreach ($permissions as $name => $description) {
            $this->insert('{{%permissions}}', [
                'name' => $name,
                'description' => $description,
                'created_at' => time(),
                'updated_at' => time(),
            ]);
        }

        // Admin szerepkörnek minden log jogosultság hozzáadása
        $adminRoleId = $this->db->createCommand('SELECT id FROM {{%roles}} WHERE name = :name')
            ->bindValue(':name', 'admin')
            ->queryScalar();

        if ($adminRoleId) {
            foreach (array_keys($permissions) as $permissionName) {
                $permissionId = $this->db->createCommand('SELECT id FROM {{%permissions}} WHERE name = :name')
                    ->bindValue(':name', $permissionName)
                    ->queryScalar();

                if ($permissionId) {
                    $this->insert('{{%role_permissions}}', [
                        'role_id' => $adminRoleId,
                        'permission_id' => $permissionId,
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
        // Log jogosultságok törlése
        $this->delete('{{%permissions}}', ['name' => ['log_view', 'log_manage']]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250817_085622_add_log_permissions cannot be reverted.\n";

        return false;
    }
    */
}
