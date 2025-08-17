<?php

namespace backend\controllers;

use Yii;
use common\models\Post;
use common\models\PostCategory;
use common\models\PostTag;
use common\models\Category;
use common\models\Tag;
use common\models\Media;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

/**
 * PostController implements WordPress-style post management functionality.
 */
class PostController extends Controller
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
                                    return $user->hasPermission('post_view') || $user->hasPermission('admin_panel');
                                case 'create':
                                    return $user->hasPermission('post_create') || $user->hasPermission('admin_panel');
                                case 'update':
                                case 'quick-edit':
                                case 'toggle-status':
                                    return $user->hasPermission('post_edit') || $user->hasPermission('admin_panel');
                                case 'view':
                                    return $user->hasPermission('post_view') || $user->hasPermission('admin_panel');
                                case 'delete':
                                case 'bulk-delete':
                                    return $user->hasPermission('post_delete') || $user->hasPermission('admin_panel');
                                default:
                                    return $user->hasPermission('post_view') || $user->hasPermission('admin_panel');
                            }
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models in WordPress style.
     * @return string
     */
    public function actionIndex()
    {
        $query = Post::find()->with(['author', 'categories', 'tags', 'featuredImage']);
        
        // Szűrés státusz szerint
        $status = Yii::$app->request->get('status');
        if ($status !== null && $status !== '') {
            $query->andWhere(['status' => $status]);
        }

        // Szűrés kategória szerint
        $categoryId = Yii::$app->request->get('category');
        if ($categoryId) {
            $query->joinWith('categories')->andWhere(['categories.id' => $categoryId]);
        }

        // Keresés
        $search = Yii::$app->request->get('search');
        if ($search) {
            $query->andWhere(['or',
                ['like', 'title', $search],
                ['like', 'content', $search],
                ['like', 'excerpt', $search]
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $categories = Category::getActive()->all();
        $authors = User::find()->all();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'categories' => $categories,
            'authors' => $authors,
            'statusFilter' => $status,
            'categoryFilter' => $categoryId,
            'searchQuery' => $search,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param int $id
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
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Post();
        $model->status = Post::STATUS_DRAFT;
        $model->visibility = Post::VISIBILITY_PUBLIC;
        $model->comment_status = Post::COMMENT_ENABLED;
        $model->author_id = Yii::$app->user->id;
        $model->seo_robots = 'index,follow';

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Publikálás dátumának beállítása
                if ($model->status == Post::STATUS_PUBLISHED && !$model->published_at) {
                    $model->published_at = time();
                }

                if ($model->save()) {
                    // Kategóriák mentése
                    $this->saveCategories($model, Yii::$app->request->post('categories', []));
                    
                    // Címkék mentése
                    $this->saveTags($model, Yii::$app->request->post('tags', []));

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Bejegyzés sikeresen létrehozva.');
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Hiba a bejegyzés mentése során.');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Hiba történt: ' . $e->getMessage());
            }
        }

        $categories = Category::getActive()->all();
        $tags = Tag::getActive()->all();
        $mediaFiles = Media::getActive()->where(['media_type' => Media::TYPE_IMAGE])->all();

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
            'tags' => $tags,
            'mediaFiles' => $mediaFiles,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Publikálás dátumának beállítása
                if ($model->status == Post::STATUS_PUBLISHED && !$model->published_at) {
                    $model->published_at = time();
                }

                if ($model->save()) {
                    // Kategóriák mentése
                    $this->saveCategories($model, Yii::$app->request->post('categories', []));
                    
                    // Címkék mentése
                    $this->saveTags($model, Yii::$app->request->post('tags', []));

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Bejegyzés sikeresen frissítve.');
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Hiba a bejegyzés mentése során.');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Hiba történt: ' . $e->getMessage());
            }
        }

        $categories = Category::getActive()->all();
        $tags = Tag::getActive()->all();
        $mediaFiles = Media::getActive()->where(['media_type' => Media::TYPE_IMAGE])->all();
        $selectedCategories = ArrayHelper::map($model->categories, 'id', 'id');
        $selectedTags = ArrayHelper::map($model->tags, 'id', 'id');

        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
            'tags' => $tags,
            'mediaFiles' => $mediaFiles,
            'selectedCategories' => $selectedCategories,
            'selectedTags' => $selectedTags,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Kapcsolatok törlése
            PostCategory::deleteAll(['post_id' => $model->id]);
            PostTag::deleteAll(['post_id' => $model->id]);
            
            $model->delete();
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Bejegyzés sikeresen törölve.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Hiba a bejegyzés törlése során: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Bulk delete posts (WordPress style)
     * @return Response
     */
    public function actionBulkDelete()
    {
        $ids = Yii::$app->request->post('selection', []);
        
        if (empty($ids)) {
            Yii::$app->session->setFlash('error', 'Nincs kiválasztott bejegyzés.');
            return $this->redirect(['index']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $count = 0;
            foreach ($ids as $id) {
                $model = $this->findModel($id);
                
                // Kapcsolatok törlése
                PostCategory::deleteAll(['post_id' => $model->id]);
                PostTag::deleteAll(['post_id' => $model->id]);
                
                if ($model->delete()) {
                    $count++;
                }
            }
            
            $transaction->commit();
            if ($count > 0) {
                Yii::$app->session->setFlash('success', "{$count} bejegyzés sikeresen törölve.");
            } else {
                Yii::$app->session->setFlash('error', 'Nem sikerült törölni a kiválasztott bejegyzéseket.');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Hiba történt: ' . $e->getMessage());
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

        if (isset($data['title'])) {
            $model->title = $data['title'];
        }
        if (isset($data['slug'])) {
            $model->slug = $data['slug'];
        }
        if (isset($data['status'])) {
            $model->status = $data['status'];
        }
        if (isset($data['visibility'])) {
            $model->visibility = $data['visibility'];
        }

        if ($model->save()) {
            return [
                'success' => true,
                'message' => 'Bejegyzés sikeresen frissítve.',
                'data' => [
                    'id' => $model->id,
                    'title' => $model->title,
                    'slug' => $model->slug,
                    'status' => $model->getStatusName(),
                    'visibility' => $model->getVisibilityName(),
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
     * Toggle post status (AJAX)
     * @param int $id
     * @return array
     */
    public function actionToggleStatus($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = $this->findModel($id);
        $model->status = $model->status == Post::STATUS_PUBLISHED ? Post::STATUS_DRAFT : Post::STATUS_PUBLISHED;
        
        // Publikálás dátumának beállítása
        if ($model->status == Post::STATUS_PUBLISHED && !$model->published_at) {
            $model->published_at = time();
        }
        
        if ($model->save(false, ['status', 'published_at'])) {
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
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A keresett bejegyzés nem található.');
    }

    /**
     * Kategóriák mentése
     * @param Post $model
     * @param array $categoryIds
     */
    private function saveCategories($model, $categoryIds)
    {
        // Régi kapcsolatok törlése
        PostCategory::deleteAll(['post_id' => $model->id]);
        
        // Új kapcsolatok létrehozása
        foreach ($categoryIds as $categoryId) {
            if ($categoryId) {
                $postCategory = new PostCategory();
                $postCategory->post_id = $model->id;
                $postCategory->category_id = $categoryId;
                $postCategory->save();
            }
        }
    }

    /**
     * Címkék mentése
     * @param Post $model
     * @param array $tagIds
     */
    private function saveTags($model, $tagIds)
    {
        // Régi kapcsolatok törlése
        PostTag::deleteAll(['post_id' => $model->id]);
        
        // Új kapcsolatok létrehozása
        foreach ($tagIds as $tagId) {
            if ($tagId) {
                $postTag = new PostTag();
                $postTag->post_id = $model->id;
                $postTag->tag_id = $tagId;
                $postTag->save();
            }
        }
    }
}
