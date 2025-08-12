<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Role model
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Permission[] $permissions
 * @property User[] $users
 * @property UserRole[] $userRoles
 * @property RolePermission[] $rolePermissions
 */
class Role extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%roles}}';
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
            [['name'], 'string', 'max' => 50],
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
            'created_at' => 'Létrehozva',
            'updated_at' => 'Frissítve',
        ];
    }

    /**
     * Gets query for [[Permissions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasMany(Permission::class, ['id' => 'permission_id'])
            ->viaTable('{{%role_permissions}}', ['role_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('{{%user_roles}}', ['role_id' => 'id']);
    }

    /**
     * Gets query for [[UserRoles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserRoles()
    {
        return $this->hasMany(UserRole::class, ['role_id' => 'id']);
    }

    /**
     * Gets query for [[RolePermissions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRolePermissions()
    {
        return $this->hasMany(RolePermission::class, ['role_id' => 'id']);
    }

    /**
     * Ellenőrzi, hogy a szerepkörnek van-e adott jogosultsága
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission($permissionName)
    {
        return $this->getPermissions()
            ->where(['name' => $permissionName])
            ->exists();
    }

    /**
     * Jogosultság hozzáadása a szerepkörhöz
     *
     * @param int $permissionId
     * @return bool
     */
    public function addPermission($permissionId)
    {
        $rolePermission = new RolePermission();
        $rolePermission->role_id = $this->id;
        $rolePermission->permission_id = $permissionId;
        $rolePermission->created_at = time();
        
        return $rolePermission->save();
    }

    /**
     * Jogosultság eltávolítása a szerepkörből
     *
     * @param int $permissionId
     * @return int
     */
    public function removePermission($permissionId)
    {
        return RolePermission::deleteAll([
            'role_id' => $this->id,
            'permission_id' => $permissionId
        ]);
    }

    /**
     * Összes jogosultság ID lekérése
     *
     * @return array
     */
    public function getPermissionIds()
    {
        return $this->getRolePermissions()
            ->select('permission_id')
            ->column();
    }
} 