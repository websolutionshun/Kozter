<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Permission model
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $category
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Role[] $roles
 * @property RolePermission[] $rolePermissions
 */
class Permission extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%permissions}}';
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
            [['name'], 'required', 'message' => '{attribute} megadása kötelező.'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 100, 'tooLong' => '{attribute} túl hosszú (maximum {max} karakter).'],
            [['category'], 'string', 'max' => 50, 'tooLong' => '{attribute} túl hosszú (maximum {max} karakter).'],
            [['name'], 'unique', 'message' => 'Ez a jogosultság név már létezik.'],
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
            'description' => 'Leírás',
            'category' => 'Kategória',
            'created_at' => 'Létrehozva',
            'updated_at' => 'Frissítve',
        ];
    }

    /**
     * Gets query for [[Roles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Role::class, ['id' => 'role_id'])
            ->viaTable('{{%role_permissions}}', ['permission_id' => 'id']);
    }

    /**
     * Gets query for [[RolePermissions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRolePermissions()
    {
        return $this->hasMany(RolePermission::class, ['permission_id' => 'id']);
    }

    /**
     * Összes kategória lekérése
     *
     * @return array
     */
    public static function getCategories()
    {
        return static::find()
            ->select('category')
            ->distinct()
            ->where(['not', ['category' => null]])
            ->column();
    }

    /**
     * Kategória alapján csoportosított jogosultságok
     *
     * @return array
     */
    public static function getByCategories()
    {
        $permissions = static::find()->orderBy('category, name')->all();
        $grouped = [];
        
        // Előre definiált kategória sorrend
        $categoryOrder = [
            'Felhasználókezelés',
            'Szerepkörkezelés', 
            'Jogosultságkezelés',
            'Kategóriakezelés',
            'Címkekezelés',
            'Médiák',
            'Bejegyzések',
            'Rendszerlogok',
            'Rendszer',
            'Egyéb'
        ];
        
        foreach ($permissions as $permission) {
            $category = $permission->category ?: 'Egyéb';
            $grouped[$category][] = $permission;
        }
        
        // Rendezett kategóriák visszaadása
        $ordered = [];
        foreach ($categoryOrder as $category) {
            if (isset($grouped[$category])) {
                $ordered[$category] = $grouped[$category];
                unset($grouped[$category]);
            }
        }
        
        // Maradék kategóriák hozzáadása
        foreach ($grouped as $category => $permissions) {
            $ordered[$category] = $permissions;
        }
        
        return $ordered;
    }
} 