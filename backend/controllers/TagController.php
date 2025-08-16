<?php

namespace backend\controllers;

use Yii;
use common\models\Tag;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;

/**
 * TagController implements WordPress-style tag management functionality.
 */
class TagController extends Controller
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
                            /** @var \common\models\User $user */
                            $user = Yii::$app->user->identity;
                            if (!$user) {
                                return false;
                            }

                            switch ($action->id) {
                                case 'index':
                                    return $user->hasPermission('tag_view') || $user->hasPermission('admin_panel');
                                case 'create':
                                    return $user->hasPermission('tag_create') || $user->hasPermission('admin_panel');
                                case 'update':
                                case 'quick-edit':
                                case 'toggle-status':
                                    return $user->hasPermission('tag_edit') || $user->hasPermission('admin_panel');
                                case 'delete':
                                case 'bulk-delete':
                                    return $user->hasPermission('tag_delete') || $user->hasPermission('admin_panel');
                                default:
                                    return $user->hasPermission('tag_view') || $user->hasPermission('admin_panel');
                            }
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Tag models in WordPress style.
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Tag::find()->orderBy(['name' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Tag model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Tag();
        $model->status = Tag::STATUS_ACTIVE;
        $model->color = '#007acc'; // Alapértelmezett szín

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Címke sikeresen létrehozva.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Hiba a címke mentése során.');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tag model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Címke sikeresen frissítve.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Hiba a címke mentése során.');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tag model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        try {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Címke sikeresen törölve.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Hiba a címke törlése során: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Bulk delete tags (WordPress style)
     * @return Response
     */
    public function actionBulkDelete()
    {
        $ids = Yii::$app->request->post('selection', []);
        
        if (empty($ids)) {
            Yii::$app->session->setFlash('error', 'Nincs kiválasztott címke.');
            return $this->redirect(['index']);
        }

        $count = 0;
        foreach ($ids as $id) {
            try {
                $model = $this->findModel($id);
                if ($model->delete()) {
                    $count++;
                }
            } catch (\Exception $e) {
                // Folytatjuk a törlést, hibát csak a végén jelentjük
            }
        }

        if ($count > 0) {
            Yii::$app->session->setFlash('success', "{$count} címke sikeresen törölve.");
        } else {
            Yii::$app->session->setFlash('error', 'Nem sikerült törölni a kiválasztott címkéket.');
        }

        return $this->redirect(['index']);
    }

    /**
     * AJAX quick edit functionality (WordPress style)
     * @param int $id
     * @return array
     */
    public function actionQuickEdit($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();

        if (isset($data['name'])) {
            $model->name = $data['name'];
        }
        if (isset($data['slug'])) {
            $model->slug = $data['slug'];
        }
        if (isset($data['color'])) {
            $model->color = $data['color'];
        }
        if (isset($data['status'])) {
            $model->status = $data['status'];
        }

        if ($model->save()) {
            return [
                'success' => true,
                'message' => 'Címke sikeresen frissítve.',
                'data' => [
                    'id' => $model->id,
                    'name' => $model->name,
                    'slug' => $model->slug,
                    'color' => $model->color,
                    'status' => $model->getStatusName(),
                    'count' => $model->count,
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Hiba a mentés során.',
                'errors' => $model->errors
            ];
        }
    }

    /**
     * Toggle tag status (AJAX)
     * @param int $id
     * @return array
     */
    public function actionToggleStatus($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = $this->findModel($id);
        $model->status = $model->status == Tag::STATUS_ACTIVE ? Tag::STATUS_INACTIVE : Tag::STATUS_ACTIVE;
        
        if ($model->save(false, ['status'])) {
            return [
                'success' => true,
                'status' => $model->status,
                'statusName' => $model->getStatusName()
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Hiba az állapot módosítása során.'
            ];
        }
    }

    /**
     * Finds the Tag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Tag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A keresett címke nem található.');
    }
}
