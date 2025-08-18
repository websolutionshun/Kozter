<?php

namespace frontend\controllers;

use common\models\Post;
use common\models\Category;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * PostController kezelő a frontend bejegyzésekhez
 */
class PostController extends Controller
{
    /**
     * Bejegyzések listázása
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Post::getPublished()->orderBy(['published_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Bejegyzés megtekintése
     */
    public function actionView($slug)
    {
        $model = Post::getPublished()->where(['slug' => $slug])->one();
        
        if (!$model) {
            throw new NotFoundHttpException('A keresett bejegyzés nem található.');
        }

        // Megtekintések számának növelése
        $model->updateCounters(['view_count' => 1]);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Kategória alapú bejegyzések
     */
    public function actionCategory($slug)
    {
        $category = Category::getActive()->where(['slug' => $slug])->one();
        
        if (!$category) {
            throw new NotFoundHttpException('A keresett kategória nem található.');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Post::getPublished()
                ->joinWith('categories')
                ->where(['{{%categories}}.id' => $category->id])
                ->orderBy(['published_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('category', [
            'category' => $category,
            'dataProvider' => $dataProvider,
        ]);
    }
}
