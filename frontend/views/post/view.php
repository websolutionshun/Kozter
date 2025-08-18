<?php

/** @var yii\web\View $this */
/** @var common\models\Post $model */

use yii\helpers\Html;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Bejegyzések', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// SEO meta tagek
if ($model->seo_title) {
    $this->title = $model->seo_title;
}
if ($model->seo_description) {
    $this->registerMetaTag(['name' => 'description', 'content' => $model->seo_description]);
}
if ($model->seo_keywords) {
    $this->registerMetaTag(['name' => 'keywords', 'content' => $model->seo_keywords]);
}
?>

<div class="post-view">
    <div class="container">
        <article class="row">
            <div class="col-lg-8 offset-lg-2">
                <!-- Post header -->
                <header class="mb-4">
                    <div class="mb-3">
                        <?php foreach ($model->categories as $category): ?>
                            <span class="badge bg-primary me-2"><?= Html::encode($category->name) ?></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <h1 class="mb-3"><?= Html::encode($model->title) ?></h1>
                    
                    <div class="d-flex justify-content-between align-items-center text-muted mb-4">
                        <div>
                            <i class="fas fa-user me-1"></i>
                            <?= Html::encode($model->author->username ?? 'Ismeretlen') ?>
                            <span class="mx-2">•</span>
                            <i class="fas fa-calendar me-1"></i>
                            <?= date('Y. F j. H:i', $model->published_at) ?>
                        </div>
                        <div>
                            <i class="fas fa-eye me-1"></i>
                            <?= number_format($model->view_count) ?> megtekintés
                        </div>
                    </div>
                    
                    <?php if ($model->featuredImage): ?>
                        <div class="text-center mb-4">
                            <img src="<?= Html::encode($model->featuredImage->path) ?>" 
                                 alt="<?= Html::encode($model->title) ?>" 
                                 class="img-fluid rounded">
                        </div>
                    <?php endif; ?>
                </header>
                
                <!-- Post content -->
                <div class="post-content mb-5">
                    <?= $model->content ?>
                </div>
                
                <!-- Post footer -->
                <footer class="border-top pt-4">
                    <?php if (!empty($model->tags)): ?>
                        <div class="mb-3">
                            <strong>Címkék:</strong>
                            <?php foreach ($model->tags as $tag): ?>
                                <span class="badge bg-secondary me-1"><?= Html::encode($tag->name) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <?= Html::a('<i class="fas fa-arrow-left"></i> Vissza a bejegyzésekhez', ['/post/index'], [
                            'class' => 'btn btn-outline-primary',
                            'encode' => false
                        ]) ?>
                        
                        <div class="social-share">
                            <span class="me-2">Megosztás:</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(\yii\helpers\Url::current([], true)) ?>" 
                               target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode(\yii\helpers\Url::current([], true)) ?>&text=<?= urlencode($model->title) ?>" 
                               target="_blank" class="btn btn-sm btn-outline-info me-1">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </footer>
            </div>
        </article>
    </div>
</div>
