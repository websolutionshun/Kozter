<?php

namespace frontend\controllers;

use common\models\Post;
use common\models\Category;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\Html;

/**
 * Ajax Controller a dinamikus tartalom betöltéshez
 */
class AjaxController extends Controller
{
    /**
     * Több bejegyzés betöltése AJAX-szal
     */
    public function actionLoadMorePosts()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $offset = (int) \Yii::$app->request->post('offset', 0);
        $limit = (int) \Yii::$app->request->post('limit', 10);
        $section = \Yii::$app->request->post('section', 'main');
        $categoryId = (int) \Yii::$app->request->post('categoryId', 0);
        
        $posts = [];
        
        switch ($section) {
            case 'main':
                $posts = Post::getPublished()
                    ->orderBy(['published_at' => SORT_DESC])
                    ->offset($offset)
                    ->limit($limit)
                    ->all();
                break;
                
            case 'category':
                if ($categoryId) {
                    $posts = Post::getPublished()
                        ->joinWith('categories')
                        ->where(['{{%categories}}.id' => $categoryId])
                        ->orderBy(['published_at' => SORT_DESC])
                        ->offset($offset)
                        ->limit($limit)
                        ->all();
                }
                break;
                
            case 'popular':
                $posts = Post::getPublished()
                    ->orderBy(['view_count' => SORT_DESC])
                    ->offset($offset)
                    ->limit($limit)
                    ->all();
                break;
        }
        
        $html = '';
        foreach ($posts as $post) {
            $html .= $this->renderPostItem($post, $section);
        }
        
        return [
            'success' => true,
            'html' => $html,
            'hasMore' => count($posts) === $limit
        ];
    }
    
    /**
     * Bejegyzés elem renderelése
     */
    private function renderPostItem($post, $section = 'main')
    {
        switch ($section) {
            case 'main':
                return $this->renderPartial('_ajax_news_item', ['post' => $post]);
                
            case 'category':
                return $this->renderPartial('_ajax_category_item', ['post' => $post]);
                
            case 'popular':
                return $this->renderPartial('_ajax_popular_item', ['post' => $post]);
                
            default:
                return $this->renderPartial('_ajax_news_item', ['post' => $post]);
        }
    }
    
    /**
     * Oszlopokra bontott bejegyzések betöltése AJAX-szal
     * ÚJ 3 oszlopos rendszerhez
     */
    public function actionLoadMoreColumnPosts()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $column = (int) \Yii::$app->request->post('column', 1);
        $offset = (int) \Yii::$app->request->post('offset', 0);
        $limit = (int) \Yii::$app->request->post('limit', 4);
        
        // Összes cikk lekérése időrendben (ugyanaz a logika mint a SiteController-ben)
        $totalOffset = 0;
        $allPosts = Post::getPublished()
            ->orderBy(['published_at' => SORT_DESC])
            ->offset($totalOffset)
            ->limit(150) // Nagy mennyiségre optimalizálva
            ->all();

        // Oszlopokra bontás (ugyanaz a logika mint a SiteController-ben)
        $columnPosts = [];
        foreach ($allPosts as $index => $post) {
            $positionInCycle = $index % 3;
            
            if (($column == 1 && $positionInCycle === 0) ||
                ($column == 2 && $positionInCycle === 1) ||
                ($column == 3 && $positionInCycle === 2)) {
                $columnPosts[] = $post;
            }
        }
        
        // Kért tartomány kivágása
        $requestedPosts = array_slice($columnPosts, $offset, $limit);
        
        $html = '';
        foreach ($requestedPosts as $index => $post) {
            $isFirst = ($offset === 0 && $index === 0); // Csak a legelső az első
            $html .= $this->renderColumnPostItem($post, $column, $isFirst);
        }
        
        // Van-e még több tartalom
        $hasMore = count($columnPosts) > ($offset + $limit);
        
        return [
            'success' => true,
            'html' => $html,
            'hasMore' => $hasMore
        ];
    }
    
    /**
     * Oszlop bejegyzés elem renderelése
     */
    private function renderColumnPostItem($post, $column, $isFirst = false)
    {
        // 2. oszlopban és első elem = LEAD formátum
        if ($column == 2 && $isFirst) {
            return $this->renderLeadPost($post);
        }
        
        // 1. és 3. oszlopban első elem = kiemelt formátum
        if (($column == 1 || $column == 3) && $isFirst) {
            return $this->renderFeaturedPost($post);
        }
        
        // Minden más = kompakt formátum
        return $this->renderCompactPost($post);
    }
    
    /**
     * LEAD bejegyzés renderelése (2. oszlop első elem)
     */
    private function renderLeadPost($post)
    {
        $html = '<article class="column-post mb-4">';
        $html .= '<div class="lead-article">';
        
        if ($post->featuredImage) {
            $html .= '<div class="lead-image mb-3">';
            $html .= '<img src="' . Html::encode($post->featuredImage->getFileUrl()) . '" ';
            $html .= 'alt="' . Html::encode($post->title) . '" class="img-fluid rounded">';
            $html .= '</div>';
        }
        
        $html .= '<div class="lead-content">';
        
        if (!empty($post->categories)) {
            $html .= '<span class="badge bg-danger-soft mb-2">VEZÉRCIKK</span>';
            $html .= '<span class="badge bg-secondary-soft mb-2 ms-1">' . Html::encode($post->categories[0]->name) . '</span>';
        } else {
            $html .= '<span class="badge bg-danger-soft mb-2">VEZÉRCIKK</span>';
        }
        
        $html .= '<h2 class="lead-title h3 mb-3">';
        $html .= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]);
        $html .= '</h2>';
        
        $html .= '<p class="lead-excerpt mb-3">' . Html::encode($post->getShortContent(200)) . '</p>';
        
        $html .= '<div class="post-meta-lead">';
        $html .= '<small class="text-muted"><i class="fas fa-user me-1"></i>';
        $html .= Html::encode($post->author->username ?? 'Szerkesztőség') . '</small>';
        $html .= '<small class="text-muted ms-3"><i class="fas fa-clock me-1"></i>';
        $html .= date('m.d H:i', $post->published_at) . '</small>';
        $html .= '</div>';
        
        $html .= '</div></div></article>';
        
        return $html;
    }
    
    /**
     * Kiemelt bejegyzés renderelése (1. és 3. oszlop első elem)
     */
    private function renderFeaturedPost($post)
    {
        $html = '<article class="column-post mb-4">';
        
        if ($post->featuredImage) {
            $html .= '<div class="post-image mb-2">';
            $html .= '<img src="' . Html::encode($post->featuredImage->getFileUrl()) . '" ';
            $html .= 'alt="' . Html::encode($post->title) . '" class="img-fluid rounded">';
            $html .= '</div>';
        }
        
        $html .= '<div class="post-content">';
        
        if (!empty($post->categories)) {
            $html .= '<span class="badge bg-primary-soft mb-2">' . Html::encode($post->categories[0]->name) . '</span>';
        }
        
        $html .= '<h3 class="post-title h4 mb-2">';
        $html .= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]);
        $html .= '</h3>';
        
        $html .= '<p class="post-excerpt mb-2">' . Html::encode($post->getShortContent(140)) . '</p>';
        
        $html .= '<div class="post-meta-main">';
        $html .= '<small class="text-muted"><i class="fas fa-clock me-1"></i>';
        $html .= date('m.d H:i', $post->published_at) . '</small>';
        $html .= '<small class="text-muted ms-3"><i class="fas fa-eye me-1"></i>';
        $html .= number_format($post->view_count) . '</small>';
        $html .= '</div>';
        
        $html .= '</div></article>';
        
        return $html;
    }
    
    /**
     * Kompakt bejegyzés renderelése
     */
    private function renderCompactPost($post)
    {
        $html = '<article class="column-post mb-4">';
        $html .= '<div class="post-compact d-flex">';
        
        if ($post->featuredImage) {
            $html .= '<div class="post-thumb me-3" style="flex-shrink: 0; width: 80px;">';
            $html .= '<img src="' . Html::encode($post->featuredImage->getFileUrl()) . '" ';
            $html .= 'alt="' . Html::encode($post->title) . '" ';
            $html .= 'class="img-fluid rounded" style="width: 80px; height: 60px; object-fit: cover;">';
            $html .= '</div>';
        }
        
        $html .= '<div class="post-info">';
        $html .= '<h6 class="post-title-small mb-1">';
        $html .= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]);
        $html .= '</h6>';
        $html .= '<div class="post-meta-small">';
        $html .= '<small class="text-muted">' . date('m.d H:i', $post->published_at) . '</small>';
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '</div></article>';
        
        return $html;
    }

    /**
     * Kategória frissítése
     */
    public function actionRefreshCategory()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $categoryId = (int) \Yii::$app->request->post('categoryId');
        $category = Category::getActive()->where(['id' => $categoryId])->one();
        
        if (!$category) {
            return ['success' => false, 'message' => 'Kategória nem található'];
        }
        
        $posts = Post::getPublished()
            ->joinWith('categories')
            ->where(['{{%categories}}.id' => $category->id])
            ->orderBy(['published_at' => SORT_DESC])
            ->limit(6)
            ->all();
        
        $html = '';
        foreach ($posts as $index => $post) {
            if ($index === 0) {
                $html .= $this->renderPartial('_ajax_category_featured', ['post' => $post]);
            } else {
                $html .= $this->renderPartial('_ajax_category_item', ['post' => $post]);
            }
        }
        
        return [
            'success' => true,
            'html' => $html,
            'categoryName' => $category->name
        ];
    }
}
