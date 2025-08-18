@echo off
REM Egyedi migráció redo script
REM Használat: redo_specific_migration.bat "m250818_112904_update_all_tables_collation_to_utf8mb4_general_ci"

if "%1"=="" (
    echo HIBA: Migracio nev szukseges!
    echo Hasznalat: %0 "MIGRATION_OSZTALY_NEV"
    echo Pelda: %0 "m250818_112904_update_all_tables_collation_to_utf8mb4_general_ci"
    exit /b 1
)

set MIGRATION_NAME=%1

echo.
echo ================================
echo    MIGRACIO REDO FUTTATASA
echo ================================
echo Migracio: %MIGRATION_NAME%
echo.

REM Eloszor ellenorizzuk, hogy a migracio lefutott-e
php yii migrate-tools/history | findstr %MIGRATION_NAME% >nul
if errorlevel 1 (
    echo Migracio meg nem futott le, csak futtatjuk...
    goto RUN_MIGRATION
)

echo 1. Migracio visszavonasa...
php yii migrate-tools/down 1 --interactive=0
if errorlevel 1 (
    echo HIBA a migracio visszavonaskor!
    exit /b 1
)

:RUN_MIGRATION
echo.
echo 2. Migracio ujrafuttatasa...
php yii migrate-tools/to %MIGRATION_NAME% --interactive=0
if errorlevel 1 (
    echo HIBA a migracio futttataskor!
    exit /b 1
)

echo.
echo SIKER: Migracio redo sikeresen befejezve!
