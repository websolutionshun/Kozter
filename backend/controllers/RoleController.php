<?php

namespace backend\controllers;

use Yii;
use common\models\Role;
use common\models\Permission;
use common\models\RolePermission;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends Controller
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
                            return Yii::$app->user->identity->hasPermission('role_view') || 
                                   Yii::$app->user->identity->hasPermission('admin_panel');
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Role models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Role::find()->with('permissions'),
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Role model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->identity->hasPermission('role_create')) {
            throw new \yii\web\ForbiddenHttpException('Nincs jogosultsága ehhez a művelethez.');
        }
        
        $model = new Role();
        $permissions = Permission::find()->all();
        $selectedPermissions = [];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Jogosultságok hozzáadása
            $selectedPermissions = Yii::$app->request->post('permissions', []);
            foreach ($selectedPermissions as $permissionId) {
                $model->addPermission($permissionId);
            }
            
            Yii::$app->session->setFlash('success', 'Szerepkör sikeresen létrehozva.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'permissions' => $permissions,
            'selectedPermissions' => $selectedPermissions,
        ]);
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->identity->hasPermission('role_edit')) {
            throw new \yii\web\ForbiddenHttpException('Nincs jogosultsága ehhez a művelethez.');
        }
        
        $model = $this->findModel($id);
        $permissions = Permission::find()->all();
        $selectedPermissions = $model->getPermissionIds();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Korábbi jogosultságok törlése
            RolePermission::deleteAll(['role_id' => $model->id]);
            
            // Új jogosultságok hozzáadása
            $newSelectedPermissions = Yii::$app->request->post('permissions', []);
            foreach ($newSelectedPermissions as $permissionId) {
                $model->addPermission($permissionId);
            }
            
            Yii::$app->session->setFlash('success', 'Szerepkör sikeresen frissítve.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'permissions' => $permissions,
            'selectedPermissions' => $selectedPermissions,
        ]);
    }

    /**
     * Deletes an existing Role model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->identity->hasPermission('role_delete')) {
            throw new \yii\web\ForbiddenHttpException('Nincs jogosultsága ehhez a művelethez.');
        }
        
        $model = $this->findModel($id);
        
        // Alapértelmezett szerepköröket nem lehet törölni
        if (in_array($model->name, ['admin', 'szerkesztő', 'szerző'])) {
            Yii::$app->session->setFlash('error', 'Az alapértelmezett szerepköröket nem lehet törölni.');
            return $this->redirect(['index']);
        }
        
        // Ellenőrizzük, hogy van-e felhasználó ezzel a szerepkörrel
        if ($model->getUsers()->count() > 0) {
            Yii::$app->session->setFlash('error', 'Nem törölhető a szerepkör, mert felhasználók használják.');
            return $this->redirect(['index']);
        }
        
        $model->delete();
        
        Yii::$app->session->setFlash('success', 'Szerepkör sikeresen törölve.');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A kért oldal nem található.');
    }
} 