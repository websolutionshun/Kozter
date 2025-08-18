<?php

use yii\db\Migration;

class m250818_140032_add_sitemap_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Sitemap jogosultságok hozzáadása
        $this->insert('{{%permissions}}', [
            'name' => 'sitemap_view',
            'description' => 'Sitemap megtekintése',
            'category' => 'sitemap',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%permissions}}', [
            'name' => 'sitemap_generate',
            'description' => 'Sitemap újragenerálása',
            'category' => 'sitemap',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        // Admin szerepkörhöz hozzáadás (ID: 1)
        $adminRoleId = 1;
        
        // Sitemap jogosultságok ID-jának lekérése
        $sitemapViewPermission = $this->db->createCommand(
            'SELECT id FROM {{%permissions}} WHERE name = :name'
        )->bindValue(':name', 'sitemap_view')->queryScalar();
        
        $sitemapGeneratePermission = $this->db->createCommand(
            'SELECT id FROM {{%permissions}} WHERE name = :name'
        )->bindValue(':name', 'sitemap_generate')->queryScalar();

        if ($sitemapViewPermission) {
            $this->insert('{{%role_permissions}}', [
                'role_id' => $adminRoleId,
                'permission_id' => $sitemapViewPermission,
                'created_at' => time(),
            ]);
        }

        if ($sitemapGeneratePermission) {
            $this->insert('{{%role_permissions}}', [
                'role_id' => $adminRoleId,
                'permission_id' => $sitemapGeneratePermission,
                'created_at' => time(),
            ]);
        }

        echo "Sitemap jogosultságok sikeresen hozzáadva.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Role permissions törlése
        $sitemapViewPermission = $this->db->createCommand(
            'SELECT id FROM {{%permissions}} WHERE name = :name'
        )->bindValue(':name', 'sitemap_view')->queryScalar();
        
        $sitemapGeneratePermission = $this->db->createCommand(
            'SELECT id FROM {{%permissions}} WHERE name = :name'
        )->bindValue(':name', 'sitemap_generate')->queryScalar();

        if ($sitemapViewPermission) {
            $this->delete('{{%role_permissions}}', ['permission_id' => $sitemapViewPermission]);
        }

        if ($sitemapGeneratePermission) {
            $this->delete('{{%role_permissions}}', ['permission_id' => $sitemapGeneratePermission]);
        }

        // Permissions törlése
        $this->delete('{{%permissions}}', ['name' => 'sitemap_view']);
        $this->delete('{{%permissions}}', ['name' => 'sitemap_generate']);

        echo "Sitemap jogosultságok sikeresen eltávolítva.\n";
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250818_140032_add_sitemap_permissions cannot be reverted.\n";

        return false;
    }
    */
}
