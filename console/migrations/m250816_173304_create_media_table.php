<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%media}}`.
 */
class m250816_173304_create_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%media}}', [
            'id' => $this->primaryKey(),
            'filename' => $this->string(255)->notNull()->comment('Fájlnév'),
            'original_name' => $this->string(255)->notNull()->comment('Eredeti fájlnév'),
            'mime_type' => $this->string(255)->notNull()->comment('MIME típus'),
            'file_path' => $this->string(255)->notNull()->comment('Fájl útvonal'),
            'file_size' => $this->integer()->notNull()->defaultValue(0)->comment('Fájlméret bájtban'),
            'media_type' => $this->string(50)->notNull()->comment('Média típus (image, video, audio, document, other)'),
            'alt_text' => $this->text()->comment('Alt szöveg képekhez'),
            'description' => $this->text()->comment('Leírás'),
            'width' => $this->integer()->comment('Szélesség pixelben (képek és videók)'),
            'height' => $this->integer()->comment('Magasság pixelben (képek és videók)'),
            'duration' => $this->integer()->comment('Időtartam másodpercben (videók és hangfájlok)'),
            'status' => $this->smallInteger()->notNull()->defaultValue(1)->comment('Állapot (0=inaktív, 1=aktív)'),
            'created_at' => $this->integer()->notNull()->comment('Létrehozás időbélyege'),
            'updated_at' => $this->integer()->notNull()->comment('Módosítás időbélyege'),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        // Indexek létrehozása
        $this->createIndex('idx-media-media_type', '{{%media}}', 'media_type');
        $this->createIndex('idx-media-status', '{{%media}}', 'status');
        $this->createIndex('idx-media-created_at', '{{%media}}', 'created_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%media}}');
    }
}
