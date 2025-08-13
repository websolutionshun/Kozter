<?php

namespace backend\controllers;

use Yii;
use common\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;

/**
 * CategoryController implements WordPress-style category management functionality.
 */
class CategoryController extends Controller
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
                                    return $user->hasPermission('category_view') || $user->hasPermission('admin_panel');
                                case 'create':
                                    return $user->hasPermission('category_create') || $user->hasPermission('admin_panel');
                                case 'update':
                                case 'quick-edit':
                                case 'toggle-status':
                                    return $user->hasPermission('category_edit') || $user->hasPermission('admin_panel');
                                case 'delete':
                                case 'bulk-delete':
                                    return $user->hasPermission('category_delete') || $user->hasPermission('admin_panel');
                                default:
                                    return $user->hasPermission('category_view') || $user->hasPermission('admin_panel');
                            }
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Category models in WordPress style.
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Category::find()->with('parent')->orderBy(['parent_id' => SORT_ASC, 'name' => SORT_ASC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $parentCategories = Category::getHierarchicalList();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'parentCategories' => $parentCategories,
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Category();
        $model->status = Category::STATUS_ACTIVE;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Kategória sikeresen létrehozva.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Hiba a kategória mentése során.');
            }
        }

        $parentCategories = [null => 'Nincs (főkategória)'] + Category::getHierarchicalList();

        return $this->render('create', [
            'model' => $model,
            'parentCategories' => $parentCategories,
        ]);
    }

    /**
     * Updates an existing Category model.
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
                Yii::$app->session->setFlash('success', 'Kategória sikeresen frissítve.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Hiba a kategória mentése során.');
            }
        }

        // Kizárjuk az aktuális kategóriát és annak leszármazottait a szülő listából
        $excludeIds = $this->getDescendantIds($id);
        $excludeIds[] = $id;
        
        // Hierarchikus lista létrehozása a kizárt ID-k nélkül
        $allCategories = Category::getHierarchicalList();
        $filteredCategories = [];
        foreach ($allCategories as $categoryId => $categoryName) {
            if (!in_array($categoryId, $excludeIds)) {
                $filteredCategories[$categoryId] = $categoryName;
            }
        }
        
        $parentCategories = [null => 'Nincs (főkategória)'] + $filteredCategories;

        return $this->render('update', [
            'model' => $model,
            'parentCategories' => $parentCategories,
        ]);
    }

    /**
     * Deletes an existing Category model.
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
            Yii::$app->session->setFlash('success', 'Kategória sikeresen törölve.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Hiba a kategória törlése során: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Bulk delete categories (WordPress style)
     * @return Response
     */
    public function actionBulkDelete()
    {
        $ids = Yii::$app->request->post('selection', []);
        
        if (empty($ids)) {
            Yii::$app->session->setFlash('error', 'Nincs kiválasztott kategória.');
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
            Yii::$app->session->setFlash('success', "{$count} kategória sikeresen törölve.");
        } else {
            Yii::$app->session->setFlash('error', 'Nem sikerült törölni a kiválasztott kategóriákat.');
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
        if (isset($data['parent_id'])) {
            $model->parent_id = $data['parent_id'] ?: null;
        }
        if (isset($data['status'])) {
            $model->status = $data['status'];
        }

        if ($model->save()) {
            return [
                'success' => true,
                'message' => 'Kategória sikeresen frissítve.',
                'data' => [
                    'id' => $model->id,
                    'name' => $model->name,
                    'slug' => $model->slug,
                    'parent' => $model->parent ? $model->parent->name : '—',
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
     * Toggle category status (AJAX)
     * @param int $id
     * @return array
     */
    public function actionToggleStatus($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = $this->findModel($id);
        $model->status = $model->status == Category::STATUS_ACTIVE ? Category::STATUS_INACTIVE : Category::STATUS_ACTIVE;
        
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
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A keresett kategória nem található.');
    }

    /**
     * Get all descendant IDs of a category
     * @param int $categoryId
     * @return array
     */
    private function getDescendantIds($categoryId)
    {
        $ids = [];
        $children = Category::find()->where(['parent_id' => $categoryId])->all();
        
        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($child->id));
        }
        
        return $ids;
    }
}
