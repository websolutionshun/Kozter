<?php
/** @var common\models\Post $post */
use yii\helpers\Html;
?>

<article class="category-featured mb-3">
    <?php if ($post->featuredImage): ?>
        <div class="category-image mb-2">
            <img src="<?= Html::encode($post->featuredImage->getFileUrl()) ?>" 
                 alt="<?= Html::encode($post->title) ?>" 
                 class="img-fluid rounded">
        </div>
    <?php endif; ?>
    
    <h5 class="category-post-title">
        <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
    </h5>
    <p class="category-excerpt"><?= Html::encode($post->getShortContent(120)) ?></p>
    <div class="post-meta-small">
        <small><?= date('m.d H:i', $post->published_at) ?></small>
    </div>
</article>
