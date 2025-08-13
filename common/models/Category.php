<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;
use yii\helpers\Inflector;

/**
 * Category model
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property integer $parent_id
 * @property integer $count
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Category $parent
 * @property Category[] $children
 */
class Category extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%categories}}';
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
            [['name'], 'required'],
            [['description'], 'string'],
            [['parent_id', 'count', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['status'], 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_ACTIVE]],
            [['parent_id'], 'exist', 'targetClass' => self::class, 'targetAttribute' => 'id'],
            // Validáció, hogy ne lehessen saját magának szülője
            [['parent_id'], 'validateParent'],
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
            'slug' => 'Slug',
            'description' => 'Leírás',
            'parent_id' => 'Szülő kategória',
            'count' => 'Elemek száma',
            'status' => 'Állapot',
            'created_at' => 'Létrehozva',
            'updated_at' => 'Frissítve',
        ];
    }

    /**
     * Szülő kategória validáció
     */
    public function validateParent($attribute, $params)
    {
        if ($this->parent_id == $this->id) {
            $this->addError($attribute, 'A kategória nem lehet saját magának szülője.');
        }
        
        // Körkörös hivatkozás ellenőrzése
        if ($this->parent_id && $this->hasCircularReference($this->parent_id)) {
            $this->addError($attribute, 'Körkörös hivatkozás nem megengedett.');
        }
    }

    /**
     * Körkörös hivatkozás ellenőrzése
     */
    private function hasCircularReference($parentId, $visited = [])
    {
        if (in_array($parentId, $visited)) {
            return true;
        }
        
        $visited[] = $parentId;
        $parent = self::findOne($parentId);
        
        if ($parent && $parent->parent_id) {
            return $this->hasCircularReference($parent->parent_id, $visited);
        }
        
        return false;
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[Children]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id'])->orderBy('name');
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
     * Aktív kategóriák lekérése
     *
     * @return \yii\db\ActiveQuery
     */
    public static function getActive()
    {
        return self::find()->where(['status' => self::STATUS_ACTIVE]);
    }

    /**
     * Hierarchikus kategória lista (WordPress stílusban)
     *
     * @param int $parentId
     * @param string $prefix
     * @return array
     */
    public static function getHierarchicalList($parentId = null, $prefix = '')
    {
        $categories = self::find()
            ->where(['parent_id' => $parentId])
            ->orderBy('name')
            ->all();

        $result = [];
        foreach ($categories as $category) {
            $result[$category->id] = $prefix . $category->name;
            $children = self::getHierarchicalList($category->id, $prefix . '— ');
            $result = array_merge($result, $children);
        }

        return $result;
    }

    /**
     * Teljes útvonal neve (szülő > gyerek formátumban)
     *
     * @return string
     */
    public function getFullPath()
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }

    /**
     * Elemek számának frissítése
     */
    public function updateCount()
    {
        // Itt később lehet implementálni a kapcsolódó elemek számolását
        // Egyelőre 0-ra állítjuk
        $this->count = 0;
        $this->save(false, ['count']);
    }

    /**
     * Kategória törlése előtti ellenőrzések
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        // Gyerek kategóriák átmozgatása vagy törlése
        $children = $this->children;
        foreach ($children as $child) {
            $child->parent_id = $this->parent_id;
            $child->save(false, ['parent_id']);
        }

        return true;
    }
}
