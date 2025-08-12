<?php

// Temp script to check role_permissions table
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/common/config/main.php';

$config = require __DIR__ . '/environments/dev/common/config/main-local.php';

// Initialize Yii application
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/common/config/bootstrap.php';

$application = new yii\console\Application($config);

try {
    // Check role_permissions table
    $db = Yii::$app->db;
    
    echo "=== ROLE_PERMISSIONS TÁBLA ELLENŐRZÉS ===\n\n";
    
    // Count total records
    $count = $db->createCommand('SELECT COUNT(*) FROM {{%role_permissions}}')->queryScalar();
    echo "Összesen rekordok száma: $count\n\n";
    
    if ($count > 0) {
        // Get all records with role and permission names
        $query = "
            SELECT 
                rp.id,
                r.name as role_name,
                p.name as permission_name,
                rp.role_id,
                rp.permission_id
            FROM {{%role_permissions}} rp
            LEFT JOIN {{%roles}} r ON rp.role_id = r.id
            LEFT JOIN {{%permissions}} p ON rp.permission_id = p.id
            ORDER BY rp.role_id, rp.permission_id
        ";
        
        $results = $db->createCommand($query)->queryAll();
        
        $currentRole = null;
        foreach ($results as $row) {
            if ($currentRole !== $row['role_name']) {
                $currentRole = $row['role_name'];
                echo "\n--- $currentRole (ID: {$row['role_id']}) ---\n";
            }
            echo "  • {$row['permission_name']} (ID: {$row['permission_id']})\n";
        }
        
        echo "\n=== ÖSSZESÍTÉS SZEREPKÖRÖNKÉNT ===\n";
        $summary = $db->createCommand('
            SELECT 
                r.name as role_name,
                COUNT(*) as permission_count
            FROM {{%role_permissions}} rp
            LEFT JOIN {{%roles}} r ON rp.role_id = r.id
            GROUP BY rp.role_id
            ORDER BY rp.role_id
        ')->queryAll();
        
        foreach ($summary as $row) {
            echo "{$row['role_name']}: {$row['permission_count']} jogosultság\n";
        }
    } else {
        echo "A tábla üres!\n";
    }
    
    echo "\n=== ELLENŐRZÉS KÉSZ ===\n";
    
} catch (Exception $e) {
    echo "Hiba: " . $e->getMessage() . "\n";
} 