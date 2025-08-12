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
            [['name'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['category'], 'string', 'max' => 50],
            [['name'], 'unique'],
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
        $permissions = static::find()->all();
        $grouped = [];
        
        foreach ($permissions as $permission) {
            $category = $permission->category ?: 'Egyéb';
            $grouped[$category][] = $permission;
        }
        
        return $grouped;
    }
} 