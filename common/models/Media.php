<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * Media model
 *
 * @property integer $id
 * @property string $filename
 * @property string $original_name
 * @property string $mime_type
 * @property string $file_path
 * @property integer $file_size
 * @property string $media_type
 * @property string $alt_text
 * @property string $description
 * @property integer $width
 * @property integer $height
 * @property integer $duration
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Media extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    const TYPE_DOCUMENT = 'document';
    const TYPE_OTHER = 'other';

    /**
     * Feltöltött fájl
     * @var UploadedFile
     */
    public $uploadedFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%media}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filename', 'original_name', 'mime_type', 'file_path'], 'required', 'message' => '{attribute} megadása kötelező.', 'on' => 'default'],
            [['file_size', 'width', 'height', 'duration', 'status', 'created_at', 'updated_at'], 'integer'],
            [['alt_text', 'description'], 'string'],
            [['filename', 'original_name', 'mime_type', 'file_path'], 'string', 'max' => 255],
            [['media_type'], 'string', 'max' => 50],
            [['status'], 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_ACTIVE]],
            [['media_type'], 'in', 'range' => [self::TYPE_IMAGE, self::TYPE_VIDEO, self::TYPE_AUDIO, self::TYPE_DOCUMENT, self::TYPE_OTHER]],
            [['uploadedFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg, jpeg, png, gif, webp, mp4, avi, mov, wmv, pdf, doc, docx, txt', 'maxSize' => 52428800, 'on' => 'upload'], // 50MB
            [['uploadedFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png, gif, webp, mp4, avi, mov, wmv, pdf, doc, docx, txt', 'maxSize' => 52428800, 'on' => 'update'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Fájlnév',
            'original_name' => 'Eredeti név',
            'mime_type' => 'MIME típus',
            'file_path' => 'Fájl útvonal',
            'file_size' => 'Fájlméret',
            'media_type' => 'Média típus',
            'alt_text' => 'Alt szöveg',
            'description' => 'Leírás',
            'width' => 'Szélesség',
            'height' => 'Magasság',
            'duration' => 'Időtartam',
            'status' => 'Állapot',
            'created_at' => 'Feltöltve',
            'updated_at' => 'Módosítva',
            'uploadedFile' => 'Fájl',
        ];
    }

    /**
     * Állapot opciók lekérése
     *
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_INACTIVE => 'Inaktív',
            self::STATUS_ACTIVE => 'Aktív',
        ];
    }

    /**
     * Média típus opciók lekérése
     *
     * @return array
     */
    public static function getMediaTypeOptions()
    {
        return [
            self::TYPE_IMAGE => 'Kép',
            self::TYPE_VIDEO => 'Videó',
            self::TYPE_AUDIO => 'Hang',
            self::TYPE_DOCUMENT => 'Dokumentum',
            self::TYPE_OTHER => 'Egyéb',
        ];
    }

    /**
     * Állapot neve
     *
     * @return string
     */
    public function getStatusName()
    {
        $options = self::getStatusOptions();
        return $options[$this->status] ?? 'Ismeretlen';
    }

    /**
     * Média típus neve
     *
     * @return string
     */
    public function getMediaTypeName()
    {
        $options = self::getMediaTypeOptions();
        return $options[$this->media_type] ?? 'Ismeretlen';
    }

    /**
     * Aktív médiák lekérése
     *
     * @return \yii\db\ActiveQuery
     */
    public static function getActive()
    {
        return self::find()->where(['status' => self::STATUS_ACTIVE]);
    }

    /**
     * Fájlméret emberi formátumban
     *
     * @return string
     */
    public function getHumanFileSize()
    {
        if (!$this->file_size) {
            return '0 B';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Média típus meghatározása MIME típus alapján
     *
     * @param string $mimeType
     * @return string
     */
    public static function detectMediaType($mimeType)
    {
        if (strpos($mimeType, 'image/') === 0) {
            return self::TYPE_IMAGE;
        } elseif (strpos($mimeType, 'video/') === 0) {
            return self::TYPE_VIDEO;
        } elseif (strpos($mimeType, 'audio/') === 0) {
            return self::TYPE_AUDIO;
        } elseif (in_array($mimeType, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            return self::TYPE_DOCUMENT;
        } else {
            return self::TYPE_OTHER;
        }
    }

    /**
     * Fájl feltöltése
     *
     * @return bool
     */
    public function upload()
    {
        // Beállítjuk az upload scenario-t a fájl validációhoz
        $this->scenario = 'upload';
        
        if (!$this->uploadedFile) {
            $this->addError('uploadedFile', 'Nincs feltöltendő fájl.');
            return false;
        }

        // Fájl validáció először a scenario alapján
        if (!$this->validate(['uploadedFile'])) {
            return false;
        }

        $uploadPath = Yii::getAlias('@frontend/web/uploads/media/');
        
        // Mappa létrehozása, ha nem létezik
        if (!is_dir($uploadPath)) {
            FileHelper::createDirectory($uploadPath, 0755, true);
        }
        
        // Egyedi fájlnév generálása
        $filename = uniqid() . '_' . time() . '.' . $this->uploadedFile->extension;
        $filePath = $uploadPath . $filename;
        
        if ($this->uploadedFile->saveAs($filePath)) {
            $this->filename = $filename;
            $this->original_name = $this->uploadedFile->baseName . '.' . $this->uploadedFile->extension;
            $this->mime_type = $this->uploadedFile->type ?: 'application/octet-stream';
            $this->file_path = 'uploads/media/' . $filename;
            $this->file_size = $this->uploadedFile->size;
            $this->media_type = self::detectMediaType($this->uploadedFile->type);
            
            // Kép méretek lekérése
            if ($this->media_type === self::TYPE_IMAGE) {
                $imageInfo = getimagesize($filePath);
                if ($imageInfo) {
                    $this->width = $imageInfo[0];
                    $this->height = $imageInfo[1];
                }
            }
            
            $this->status = self::STATUS_ACTIVE;
            
            // Váltás vissza az alapértelmezett scenario-ra a teljes validációhoz
            $this->scenario = 'default';
            
            // Most validáljuk a modellt a kitöltött adatokkal
            if ($this->validate()) {
                return $this->save(false); // false paraméter, mert már validáltuk
            } else {
                // Ha a validáció sikertelen, töröljük a fájlt
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                return false;
            }
        }
        
        $this->addError('uploadedFile', 'Hiba történt a fájl mentése során.');
        return false;
    }

    /**
     * Fájl útvonal lekérése (kompatibilitás érdekében)
     *
     * @return string
     */
    public function getPath()
    {
        return $this->file_path;
    }

    /**
     * Teljes fájl URL lekérése
     *
     * @return string
     */
    public function getFileUrl()
    {
        // Frontend URL használata a paraméterekből
        $frontendUrl = Yii::$app->params['frontendUrl'] ?? '';
        if ($frontendUrl) {
            return $frontendUrl . '/' . $this->file_path;
        }
        
        // Ha nincs beállítva a frontendUrl, akkor relatív útvonal
        return '/' . $this->file_path;
    }

    /**
     * Thumbnail URL lekérése képekhez
     *
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getThumbnailUrl($width = 150, $height = 150)
    {
        if ($this->media_type !== self::TYPE_IMAGE) {
            return null;
        }
        
        // Egyszerű thumbnail URL - később kiegészíthető képmanipulációs könyvtárral
        return $this->getFileUrl();
    }

    /**
     * Fájl törlése előtti cleanup
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        // Fizikai fájl törlése
        $filePath = Yii::getAlias('@frontend/web/') . $this->file_path;
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        return true;
    }
}
