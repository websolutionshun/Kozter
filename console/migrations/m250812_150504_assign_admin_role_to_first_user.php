<?php

use yii\db\Migration;

class m250812_150504_assign_admin_role_to_first_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Az első felhasználó (általában ID=1) admin szerepkör hozzárendelése
        $firstUser = $this->db->createCommand('SELECT id FROM {{%user}} ORDER BY id ASC LIMIT 1')->queryScalar();
        $adminRole = $this->db->createCommand('SELECT id FROM {{%roles}} WHERE name = :name', [':name' => 'admin'])->queryScalar();
        
        if ($firstUser && $adminRole) {
            // Ellenőrizzük, hogy már nincs-e hozzárendelve
            $exists = $this->db->createCommand('SELECT COUNT(*) FROM {{%user_roles}} WHERE user_id = :user_id AND role_id = :role_id', [
                ':user_id' => $firstUser,
                ':role_id' => $adminRole
            ])->queryScalar();
            
            if ($exists == 0) {
                $this->insert('{{%user_roles}}', [
                    'user_id' => $firstUser,
                    'role_id' => $adminRole,
                    'created_at' => time(),
                ]);
                
                echo "Admin szerepkör hozzárendelve a felhasználóhoz (ID: $firstUser)\n";
            } else {
                echo "Az admin szerepkör már hozzá van rendelve a felhasználóhoz\n";
            }
        } else {
            echo "Nem található felhasználó vagy admin szerepkör\n";
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Az első felhasználótól az admin szerepkör eltávolítása
        $firstUser = $this->db->createCommand('SELECT id FROM {{%user}} ORDER BY id ASC LIMIT 1')->queryScalar();
        $adminRole = $this->db->createCommand('SELECT id FROM {{%roles}} WHERE name = :name', [':name' => 'admin'])->queryScalar();
        
        if ($firstUser && $adminRole) {
            $this->delete('{{%user_roles}}', [
                'user_id' => $firstUser,
                'role_id' => $adminRole
            ]);
            
            echo "Admin szerepkör eltávolítva a felhasználótól (ID: $firstUser)\n";
        }
        
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250812_150504_assign_admin_role_to_first_user cannot be reverted.\n";

        return false;
    }
    */
}
