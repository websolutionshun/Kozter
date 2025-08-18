<?php

/** @var yii\web\View $this */
/** @var common\models\Post $model */

use yii\helpers\Html;
?>

<div class="post-card p-4 h-100">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <div class="badge bg-primary"><?= Html::encode($model->getCategoriesText()) ?></div>
        <small class="post-meta"><?= date('Y.m.d H:i', $model->published_at) ?></small>
    </div>
    
    <h4 class="post-title mb-3">
        <?= Html::a(Html::encode($model->title), ['/post/view', 'slug' => $model->slug]) ?>
    </h4>
    
    <?php if ($model->featuredImage): ?>
        <div class="mb-3">
            <img src="<?= Html::encode($model->featuredImage->path) ?>" 
                 alt="<?= Html::encode($model->title) ?>" 
                 class="img-fluid rounded">
        </div>
    <?php endif; ?>
    
    <p class="post-excerpt mb-3"><?= Html::encode($model->getShortContent(150)) ?></p>
    
    <div class="d-flex justify-content-between align-items-center">
        <div class="post-meta">
            <small>
                <i class="fas fa-user me-1"></i>
                <?= Html::encode($model->author->username ?? 'Ismeretlen') ?>
            </small>
            <small class="ms-3">
                <i class="fas fa-eye me-1"></i>
                <?= number_format($model->view_count) ?> megtekintés
            </small>
        </div>
        
        <?= Html::a('Tovább olvasom', ['/post/view', 'slug' => $model->slug], ['class' => 'btn btn-kozter btn-sm']) ?>
    </div>
</div>
