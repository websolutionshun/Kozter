<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

/**
 * Log model
 *
 * @property int $id
 * @property string $level
 * @property string|null $category
 * @property string $message
 * @property string|null $data
 * @property int|null $user_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $url
 * @property string|null $method
 * @property int $created_at
 *
 * @property User $user
 */
class Log extends ActiveRecord
{
    // Log szintek konstansai
    const LEVEL_ERROR = 'error';
    const LEVEL_WARNING = 'warning';
    const LEVEL_INFO = 'info';
    const LEVEL_SUCCESS = 'success';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%logs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false, // Nincs updated_at oszlop
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'message'], 'required'],
            [['message', 'data', 'user_agent'], 'string'],
            [['user_id', 'created_at'], 'integer'],
            [['level'], 'string', 'max' => 20],
            [['category'], 'string', 'max' => 255],
            [['ip_address'], 'string', 'max' => 45],
            [['url'], 'string', 'max' => 2048],
            [['method'], 'string', 'max' => 10],
            ['level', 'in', 'range' => [self::LEVEL_ERROR, self::LEVEL_WARNING, self::LEVEL_INFO, self::LEVEL_SUCCESS]],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level' => 'Szint',
            'category' => 'Kategória',
            'message' => 'Üzenet',
            'data' => 'Adatok',
            'user_id' => 'Felhasználó',
            'ip_address' => 'IP cím',
            'user_agent' => 'User Agent',
            'url' => 'URL',
            'method' => 'Metódus',
            'created_at' => 'Létrehozva',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Visszaadja az összes szint listáját
     */
    public static function getLevels()
    {
        return [
            self::LEVEL_ERROR => 'Hiba',
            self::LEVEL_WARNING => 'Figyelmeztetés', 
            self::LEVEL_INFO => 'Információ',
            self::LEVEL_SUCCESS => 'Siker',
        ];
    }

    /**
     * Szint címkéjének lekérése
     */
    public function getLevelLabel()
    {
        $levels = self::getLevels();
        return $levels[$this->level] ?? $this->level;
    }

    /**
     * Szint badge osztályának lekérése
     */
    public function getLevelBadgeClass()
    {
        $classes = [
            self::LEVEL_ERROR => 'bg-red-lt',
            self::LEVEL_WARNING => 'bg-yellow-lt',
            self::LEVEL_INFO => 'bg-blue-lt',
            self::LEVEL_SUCCESS => 'bg-green-lt',
        ];
        return $classes[$this->level] ?? 'bg-secondary-lt';
    }

    /**
     * Adatok dekódolása JSON-ból
     */
    public function getDecodedData()
    {
        if (empty($this->data)) {
            return null;
        }
        try {
            return Json::decode($this->data);
        } catch (\Exception $e) {
            return $this->data;
        }
    }

    /**
     * Adatok kódolása JSON-ba
     */
    public function setDataArray($data)
    {
        if (is_array($data) || is_object($data)) {
            $this->data = Json::encode($data);
        } else {
            $this->data = $data;
        }
    }

    /**
     * Létrehoz egy új log bejegyzést
     */
    public static function createLog($level, $message, $category = null, $data = null)
    {
        $log = new self();
        $log->level = $level;
        $log->message = $message;
        $log->category = $category;
        
        if ($data !== null) {
            $log->setDataArray($data);
        }

        // Aktuális felhasználó adatainak beállítása (csak webes alkalmazásban)
        if (!Yii::$app instanceof \yii\console\Application && !Yii::$app->user->isGuest) {
            $log->user_id = Yii::$app->user->id;
        }

        // Request adatok beállítása
        if (!Yii::$app instanceof \yii\console\Application) {
            $request = Yii::$app->request;
            $log->ip_address = $request->userIP;
            $log->user_agent = $request->userAgent;
            $log->url = $request->absoluteUrl;
            $log->method = $request->method;
        }

        $log->save();
        return $log;
    }

    /**
     * Shortcut metódusok különböző log szintekhez
     */
    public static function error($message, $category = null, $data = null)
    {
        return self::createLog(self::LEVEL_ERROR, $message, $category, $data);
    }

    public static function warning($message, $category = null, $data = null)
    {
        return self::createLog(self::LEVEL_WARNING, $message, $category, $data);
    }

    public static function info($message, $category = null, $data = null)
    {
        return self::createLog(self::LEVEL_INFO, $message, $category, $data);
    }

    public static function success($message, $category = null, $data = null)
    {
        return self::createLog(self::LEVEL_SUCCESS, $message, $category, $data);
    }

    /**
     * Formázott dátum megjelenítése
     */
    public function getFormattedCreatedAt()
    {
        return date('Y-m-d H:i:s', $this->created_at);
    }

    /**
     * Relatív idő megjelenítése
     */
    public function getRelativeTime()
    {
        return Yii::$app->formatter->asRelativeTime($this->created_at);
    }

    /**
     * Rövidített üzenet
     */
    public function getShortMessage($length = 100)
    {
        if (mb_strlen($this->message) <= $length) {
            return $this->message;
        }
        return mb_substr($this->message, 0, $length) . '...';
    }
}
