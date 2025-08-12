<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\Role;
use common\models\UserRole;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                            return Yii::$app->user->identity->hasPermission('admin_panel');
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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->with('roles'),
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
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new User();
        $roles = Role::find()->all();
        $selectedRoles = [];

        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password);
            $model->generateAuthKey();
            $model->status = User::STATUS_ACTIVE;
            $model->generateEmailVerificationToken();
            
            if ($model->save()) {
                // Szerepkör hozzáadása
                $selectedRole = Yii::$app->request->post('role');
                if (!empty($selectedRole)) {
                    $model->addRole($selectedRole);
                }
                
                Yii::$app->session->setFlash('success', 'Felhasználó sikeresen létrehozva.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'roles' => $roles,
            'selectedRoles' => $selectedRoles,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $roles = Role::find()->all();
        $selectedRoles = $model->getRoleIds();

        if ($model->load(Yii::$app->request->post())) {
            // Új jelszó beállítása ha megadták
            $newPassword = Yii::$app->request->post('new_password');
            if (!empty($newPassword)) {
                $model->setPassword($newPassword);
                $model->generateAuthKey();
            }
            
            if ($model->save()) {
                // Korábbi szerepkörök törlése
                UserRole::deleteAll(['user_id' => $model->id]);
                
                // Új szerepkör hozzáadása
                $newSelectedRole = Yii::$app->request->post('role');
                if (!empty($newSelectedRole)) {
                    $model->addRole($newSelectedRole);
                }
                
                Yii::$app->session->setFlash('success', 'Felhasználó sikeresen frissítve.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'roles' => $roles,
            'selectedRoles' => $selectedRoles,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        // Saját magát nem törölheti
        if ($model->id == Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', 'Saját magát nem törölheti.');
            return $this->redirect(['index']);
        }
        
        // Soft delete - status változtatás
        $model->status = User::STATUS_DELETED;
        $model->save(false);
        
        Yii::$app->session->setFlash('success', 'Felhasználó sikeresen törölve.');
        return $this->redirect(['index']);
    }

    /**
     * Jelszó visszaállítása
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionResetPassword($id)
    {
        $model = $this->findModel($id);
        
        if (Yii::$app->request->isPost) {
            $newPassword = Yii::$app->request->post('password');
            if (!empty($newPassword)) {
                $model->setPassword($newPassword);
                $model->generateAuthKey();
                $model->removePasswordResetToken();
                
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Jelszó sikeresen megváltoztatva.');
                } else {
                    Yii::$app->session->setFlash('error', 'Hiba történt a jelszó megváltoztatása során.');
                }
            }
        }
        
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A kért oldal nem található.');
    }
} 