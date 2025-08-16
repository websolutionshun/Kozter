<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * Tag model
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $color
 * @property integer $count
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Tag extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tags}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => false,
                'ensureUnique' => true,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required', 'message' => '{attribute} megadása kötelező.'],
            [['description'], 'string'],
            [['count', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255, 'tooLong' => '{attribute} túl hosszú (maximum {max} karakter).'],
            [['color'], 'string', 'max' => 7, 'tooLong' => '{attribute} túl hosszú (maximum {max} karakter).'],
            [['color'], 'match', 'pattern' => '/^#[0-9A-Fa-f]{6}$/', 'message' => 'A szín helyes hex formátumban kell legyen (pl. #FF5733)'],
            [['slug'], 'unique', 'message' => 'Ez az URL név már használatban van.'],
            [['status'], 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_ACTIVE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Név',
            'slug' => 'URL név',
            'description' => 'Leírás',
            'color' => 'Szín',
            'count' => 'Elemek száma',
            'status' => 'Állapot',
            'created_at' => 'Létrehozva',
            'updated_at' => 'Módosítva',
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
     * Aktív címkék lekérése
     *
     * @return \yii\db\ActiveQuery
     */
    public static function getActive()
    {
        return self::find()->where(['status' => self::STATUS_ACTIVE]);
    }

    /**
     * Címkék lista lekérése dropdown-hoz
     *
     * @return array
     */
    public static function getList()
    {
        return self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->orderBy('name')
            ->indexBy('id')
            ->column();
    }

    /**
     * Alapértelmezett színek címkékhez
     *
     * @return array
     */
    public static function getDefaultColors()
    {
        return [
            '#007acc', // Kék
            '#28a745', // Zöld
            '#dc3545', // Piros
            '#ffc107', // Sárga
            '#6f42c1', // Lila
            '#fd7e14', // Narancs
            '#20c997', // Türkiz
            '#e83e8c', // Pink
            '#6c757d', // Szürke
            '#17a2b8', // Világoskék
        ];
    }

    /**
     * Elemek számának frissítése
     */
    public function updateCount()
    {
        // Itt később lehet implementálni a kapcsolódó elemek számának frissítését
        // pl. cikkekhez vagy más tartalmakhoz kapcsolt címkék száma
        return true;
    }
}
