<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * UserRole model
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $role_id
 * @property integer $created_at
 *
 * @property User $user
 * @property Role $role
 */
class UserRole extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_roles}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'role_id'], 'required', 'message' => '{attribute} megadása kötelező.'],
            [['user_id', 'role_id', 'created_at'], 'integer'],
            [['user_id', 'role_id'], 'unique', 'targetAttribute' => ['user_id', 'role_id'], 'message' => 'Ez a felhasználó már rendelkezik ezzel a szerepkörrel.'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id'], 'message' => 'A kiválasztott felhasználó nem létezik.'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id'], 'message' => 'A kiválasztott szerepkör nem létezik.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Felhasználó ID',
            'role_id' => 'Szerepkör ID',
            'created_at' => 'Létrehozva',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = time();
            }
            return true;
        }
        return false;
    }
} 