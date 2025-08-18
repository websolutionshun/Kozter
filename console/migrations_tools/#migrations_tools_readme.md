# Migrations Tools

**Cél:** Migrációs fájlok újrafuttatása bármikor

## Használat

```cmd
console\migrations_tools\redo_specific_migration.bat "m250818_112904_update_all_tables_collation_to_utf8mb4_general_ci"
```

**Eredmény:**
- Visszavonja a migrációt (`down`)
- Újra futtatja (`up`)
- Külön `migration_tools` táblát használ

## Telepítés új projektbe

Add hozzá a `console/config/main.php` fájlhoz:

```php
'controllerMap' => [
    'migrate-tools' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationPath' => '@console/migrations_tools',
        'migrationTable' => '{{%migration_tools}}', // Külön tábla a tools migrációkhoz
    ],
    // ... egyéb controllerek
],
```

## Fájlok

- `redo_specific_migration.bat` - Script a migrációk újrafuttatásához
- `m250818_112904_update_all_tables_collation_to_utf8mb4_general_ci.php` - Collation frissítő migráció

## ⚠️ Fontos

**Mindig készíts adatbázis backup-ot futtatás előtt!**
