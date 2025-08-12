<?php

namespace backend\controllers;

use Yii;
use common\models\Permission;
use common\models\Role;
use common\models\RolePermission;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * PermissionController implements permission management functionality.
 */
class PermissionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->hasPermission('permission_view') || 
                                   Yii::$app->user->identity->hasPermission('admin_panel');
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Jogosultságkezelő főoldal - táblázat nézet
     * Megjeleníti a jogosultságokat kategóriák szerint, szerepkörök oszlopaival
     *
     * @return string
     */
    public function actionIndex()
    {
        $permissions = Permission::getByCategories();
        $roles = Role::find()->orderBy('id')->all();
        
        // Jelenlegi jogosultság-szerepkör kapcsolatok lekérése
        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role->id] = $role->getPermissionIds();
        }

        return $this->render('index', [
            'permissions' => $permissions,
            'roles' => $roles,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    /**
     * Jogosultság kapcsoló AJAX végpont
     * Beállítja vagy eltávolítja a jogosultságot egy szerepkörtől
     *
     * @return Response
     */
    public function actionToggle()
    {
        if (!Yii::$app->user->identity->hasPermission('permission_manage')) {
            return $this->asJson(['success' => false, 'message' => 'Nincs jogosultsága ehhez a művelethez.']);
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $roleId = Yii::$app->request->post('role_id');
        $permissionId = Yii::$app->request->post('permission_id');
        $enabledValue = Yii::$app->request->post('enabled');
        
        $enabled = ($enabledValue === true || $enabledValue === 'true' || $enabledValue === '1' || $enabledValue === 1);
        
        if (!$roleId || !$permissionId) {
            return ['success' => false, 'message' => 'Hiányzó paraméterek.'];
        }
        
        $role = Role::findOne($roleId);
        $permission = Permission::findOne($permissionId);
        
        if (!$role || !$permission) {
            return ['success' => false, 'message' => 'Szerepkör vagy jogosultság nem található.'];
        }
        
        $existing = RolePermission::findOne(['role_id' => $roleId, 'permission_id' => $permissionId]);
        
        if ($enabled) {
            // Jogosultság hozzáadása
            if (!$existing) {
                $rolePermission = new RolePermission();
                $rolePermission->role_id = $roleId;
                $rolePermission->permission_id = $permissionId;
                $rolePermission->created_at = time();
                
                if ($rolePermission->save()) {
                    return ['success' => true, 'message' => 'Jogosultság hozzáadva.'];
                } else {
                    return ['success' => false, 'message' => 'Hiba történt a jogosultság hozzáadása során.'];
                }
            } else {
                return ['success' => true, 'message' => 'Jogosultság már hozzá volt adva.'];
            }
        } else {
            if ($existing) {
                $deleted = RolePermission::deleteAll(['role_id' => $roleId, 'permission_id' => $permissionId]);
                if ($deleted > 0) {
                    return ['success' => true, 'message' => 'Jogosultság eltávolítva.'];
                } else {
                    return ['success' => false, 'message' => 'Hiba történt a jogosultság eltávolítása során.'];
                }
            } else {
                return ['success' => true, 'message' => 'Jogosultság már el volt távolítva.'];
            }
        }
    }

    /**
     * Szerepkör összes jogosultságának beállítása
     *
     * @return Response
     */
    public function actionSetRolePermissions()
    {
        if (!Yii::$app->user->identity->hasPermission('permission_manage')) {
            return $this->asJson(['success' => false, 'message' => 'Nincs jogosultsága ehhez a művelethez.']);
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $roleId = Yii::$app->request->post('role_id');
        $permissionIds = Yii::$app->request->post('permission_ids', []);
        
        if (!$roleId) {
            return ['success' => false, 'message' => 'Hiányzó szerepkör ID.'];
        }
        
        $role = Role::findOne($roleId);
        if (!$role) {
            return ['success' => false, 'message' => 'Szerepkör nem található.'];
        }
        
        // Összes korábbi jogosultság törlése
        RolePermission::deleteAll(['role_id' => $roleId]);
        
        // Új jogosultságok hozzáadása
        $time = time();
        foreach ($permissionIds as $permissionId) {
            $rolePermission = new RolePermission();
            $rolePermission->role_id = $roleId;
            $rolePermission->permission_id = $permissionId;
            $rolePermission->created_at = $time;
            $rolePermission->save();
        }
        
        return ['success' => true, 'message' => 'Szerepkör jogosultságai frissítve.'];
    }

    /**
     * Jogosultság összes szerepkörének beállítása
     *
     * @return Response
     */
    public function actionSetPermissionRoles()
    {
        if (!Yii::$app->user->identity->hasPermission('permission_manage')) {
            return $this->asJson(['success' => false, 'message' => 'Nincs jogosultsága ehhez a művelethez.']);
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $permissionId = Yii::$app->request->post('permission_id');
        $roleIds = Yii::$app->request->post('role_ids', []);
        
        if (!$permissionId) {
            return ['success' => false, 'message' => 'Hiányzó jogosultság ID.'];
        }
        
        $permission = Permission::findOne($permissionId);
        if (!$permission) {
            return ['success' => false, 'message' => 'Jogosultság nem található.'];
        }
        
        // Összes korábbi kapcsolat törlése
        RolePermission::deleteAll(['permission_id' => $permissionId]);
        
        // Új kapcsolatok hozzáadása
        $time = time();
        foreach ($roleIds as $roleId) {
            $rolePermission = new RolePermission();
            $rolePermission->role_id = $roleId;
            $rolePermission->permission_id = $permissionId;
            $rolePermission->created_at = $time;
            $rolePermission->save();
        }
        
        return ['success' => true, 'message' => 'Jogosultság szerepkörei frissítve.'];
    }
} 