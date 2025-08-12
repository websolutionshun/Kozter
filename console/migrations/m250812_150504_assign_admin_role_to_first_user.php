<?php

use yii\db\Migration;

class m250812_150504_assign_admin_role_to_first_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Az 1-es ID-jű felhasználónak admin szerepkör (ID=1) hozzárendelése
        $userId = 1; // Specifikusan az 1-es ID-jű felhasználó
        $adminRoleId = 1; // Admin szerepkör ID-ja (a roles táblában az első elem)
        
        // Ellenőrizzük, hogy létezik-e az 1-es ID-jű felhasználó
        $userExists = $this->db->createCommand('SELECT COUNT(*) FROM {{%user}} WHERE id = :id', [':id' => $userId])->queryScalar();
        
        if (!$userExists) {
            echo "Az 1-es ID-jű felhasználó nem található. Létrehozás admin jogosultsággal...\n";
            
            // Admin felhasználó létrehozása
            $now = time();
            $authKey = Yii::$app->security->generateRandomString(32);
            $passwordHash = Yii::$app->security->generatePasswordHash('admin123'); // Alapértelmezett jelszó
            
            $this->insert('{{%user}}', [
                'id' => $userId,
                'username' => 'admin',
                'email' => 'admin@kozter.com',
                'auth_key' => $authKey,
                'password_hash' => $passwordHash,
                'status' => 10, // STATUS_ACTIVE
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            
            echo "Admin felhasználó létrehozva (username: admin, email: admin@kozter.com, jelszó: admin123)\n";
        }
        
        // Ellenőrizzük, hogy létezik-e az admin szerepkör
        $roleExists = $this->db->createCommand('SELECT COUNT(*) FROM {{%roles}} WHERE id = :id', [':id' => $adminRoleId])->queryScalar();
        
        if (!$roleExists) {
            echo "Hiba: Nem található admin szerepkör (ID: $adminRoleId)\n";
            return false;
        }
        
        // Ellenőrizzük, hogy már nincs-e hozzárendelve
        $exists = $this->db->createCommand('SELECT COUNT(*) FROM {{%user_roles}} WHERE user_id = :user_id AND role_id = :role_id', [
            ':user_id' => $userId,
            ':role_id' => $adminRoleId
        ])->queryScalar();
        
        if ($exists == 0) {
            $this->insert('{{%user_roles}}', [
                'user_id' => $userId,
                'role_id' => $adminRoleId,
                'created_at' => time(),
            ]);
            
            echo "Admin szerepkör (ID: $adminRoleId) sikeresen hozzárendelve az 1-es ID-jű felhasználóhoz\n";
            echo "A felhasználó most már rendelkezik minden jogosultsággal (12 permission)\n";
        } else {
            echo "Az admin szerepkör már hozzá van rendelve az 1-es ID-jű felhasználóhoz\n";
        }
        
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Az 1-es ID-jű felhasználótól az admin szerepkör (ID=1) eltávolítása
        $userId = 1; // Specifikusan az 1-es ID-jű felhasználó
        $adminRoleId = 1; // Admin szerepkör ID-ja
        
        // Ellenőrizzük, hogy létezik-e a hozzárendelés
        $exists = $this->db->createCommand('SELECT COUNT(*) FROM {{%user_roles}} WHERE user_id = :user_id AND role_id = :role_id', [
            ':user_id' => $userId,
            ':role_id' => $adminRoleId
        ])->queryScalar();
        
        if ($exists > 0) {
            $this->delete('{{%user_roles}}', [
                'user_id' => $userId,
                'role_id' => $adminRoleId
            ]);
            
            echo "Admin szerepkör eltávolítva az 1-es ID-jű felhasználótól\n";
        }
        
        // Ellenőrizzük, hogy az 1-es ID-jű felhasználó az 'admin' felhasználó-e
        // (ezt valószínűleg ez a migráció hozta létre)
        $user = $this->db->createCommand('SELECT username, email FROM {{%user}} WHERE id = :id', [':id' => $userId])->queryOne();
        
        if ($user && $user['username'] === 'admin' && $user['email'] === 'admin@kozter.com') {
            echo "Az admin felhasználó törlése (valószínűleg ez a migráció hozta létre)...\n";
            $this->delete('{{%user}}', ['id' => $userId]);
            echo "Admin felhasználó törölve\n";
        } else {
            echo "Az 1-es ID-jű felhasználó nem az admin felhasználó, nem törlöm\n";
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
