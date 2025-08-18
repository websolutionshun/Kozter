<?php

use yii\db\Migration;

class m250818_112904_update_all_tables_collation_to_utf8mb4_general_ci extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Dinamikusan lekérdezzük az összes tábla nevét az adatbázisból
        $databaseName = $this->db->createCommand("SELECT DATABASE()")->queryScalar();
        echo "Working with database: {$databaseName}\n";
        
        // Ideiglenes tábla létrehozása az eredeti collation-ok tárolásához (ha még nem létezik)
        $backupTableExists = $this->db->createCommand("SHOW TABLES LIKE 'migration_collation_backup'")->queryScalar();
        
        if (!$backupTableExists) {
            $this->createTable('{{%migration_collation_backup}}', [
                'id' => $this->primaryKey(),
                'table_name' => $this->string(64)->notNull(),
                'original_collation' => $this->string(64)->notNull(),
                'migration_class' => $this->string(255)->notNull(),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            ]);
            echo "Created backup table 'migration_collation_backup'.\n";
        } else {
            echo "Backup table 'migration_collation_backup' already exists.\n";
        }
        
        // Lekérdezzük az összes tábla nevét
        $tables = $this->db->createCommand("SHOW TABLES")->queryColumn();
        
        echo "Found " . count($tables) . " tables in the database.\n";
        
        foreach ($tables as $table) {
            try {
                // Kihagyjuk a most létrehozott backup táblát
                if ($table === 'migration_collation_backup') {
                    continue;
                }
                
                // Lekérdezzük a tábla jelenlegi karakterkészletét és collation-ját
                $tableInfo = $this->db->createCommand("
                    SELECT TABLE_COLLATION, TABLE_SCHEMA 
                    FROM information_schema.TABLES 
                    WHERE TABLE_SCHEMA = '{$databaseName}' AND TABLE_NAME = '{$table}'
                ")->queryOne();
                
                if ($tableInfo) {
                    $currentCollation = $tableInfo['TABLE_COLLATION'];
                    echo "Table '{$table}' current collation: {$currentCollation}\n";
                    
                    // Elmentjük az eredeti collation-t a backup táblába
                    $this->insert('{{%migration_collation_backup}}', [
                        'table_name' => $table,
                        'original_collation' => $currentCollation,
                        'migration_class' => self::class,
                    ]);
                    
                    // Csak akkor módosítjuk, ha nem már utf8mb4_general_ci
                    if ($currentCollation !== 'utf8mb4_general_ci') {
                        // Tábla collation módosítása
                        $this->execute("ALTER TABLE `{$table}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
                        echo "✓ Updated table '{$table}' from '{$currentCollation}' to utf8mb4_general_ci.\n";
                    } else {
                        echo "→ Table '{$table}' already has utf8mb4_general_ci collation, skipping.\n";
                    }
                } else {
                    echo "! Could not get collation info for table '{$table}', skipping.\n";
                }
            } catch (Exception $e) {
                echo "✗ Error updating table '{$table}': " . $e->getMessage() . "\n";
            }
        }
        
        echo "Collation update process completed.\n";
        echo "Original collations backed up to 'migration_collation_backup' table.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        try {
            // Ellenőrizzük, hogy létezik-e a backup tábla
            $backupTableExists = $this->db->createCommand("SHOW TABLES LIKE 'migration_collation_backup'")->queryScalar();
            
            if (!$backupTableExists) {
                echo "ERROR: Backup table 'migration_collation_backup' not found!\n";
                echo "Cannot safely revert collation changes.\n";
                return false;
            }
            
            // Lekérdezzük az eredeti collation-okat
            $backupData = $this->db->createCommand("
                SELECT table_name, original_collation 
                FROM {{%migration_collation_backup}} 
                WHERE migration_class = :class
            ", [':class' => self::class])->queryAll();
            
            if (empty($backupData)) {
                echo "No backup data found for this migration.\n";
                return false;
            }
            
            echo "Restoring original collations from backup...\n";
            echo "WARNING: This may cause data loss if utf8mb4 specific characters are present!\n";
            
            foreach ($backupData as $backup) {
                $tableName = $backup['table_name'];
                $originalCollation = $backup['original_collation'];
                
                try {
                    // Ellenőrizzük, hogy a tábla még létezik-e
                    $tableExists = $this->db->createCommand("SHOW TABLES LIKE '{$tableName}'")->queryScalar();
                    
                    if ($tableExists) {
                        // Karakterkészlet meghatározása a collation alapján
                        $charset = 'utf8mb4';
                        if (strpos($originalCollation, 'utf8mb3') === 0) {
                            $charset = 'utf8mb3';
                        } elseif (strpos($originalCollation, 'latin1') === 0) {
                            $charset = 'latin1';
                        } elseif (strpos($originalCollation, 'utf8_') === 0) {
                            $charset = 'utf8';
                        }
                        
                        // Visszaállítjuk az eredeti collation-t
                        $this->execute("ALTER TABLE `{$tableName}` CONVERT TO CHARACTER SET {$charset} COLLATE {$originalCollation}");
                        echo "✓ Restored table '{$tableName}' to original collation: {$originalCollation}\n";
                    } else {
                        echo "! Table '{$tableName}' no longer exists, skipping.\n";
                    }
                } catch (Exception $e) {
                    echo "✗ Error restoring table '{$tableName}': " . $e->getMessage() . "\n";
                }
            }
            
            // Töröljük a backup rekordokat ehhez a migrációhoz
            $this->delete('{{%migration_collation_backup}}', ['migration_class' => self::class]);
            
            // Ha nincs több backup rekord, töröljük a backup táblát is
            $remainingBackups = $this->db->createCommand("SELECT COUNT(*) FROM {{%migration_collation_backup}}")->queryScalar();
            if ($remainingBackups == 0) {
                $this->dropTable('{{%migration_collation_backup}}');
                echo "Backup table removed (no more backup records).\n";
            }
            
            echo "Collation restoration completed.\n";
            
        } catch (Exception $e) {
            echo "ERROR during collation restoration: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250818_112904_update_all_tables_collation_to_utf8mb4_general_ci cannot be reverted.\n";

        return false;
    }
    */
}
