<?php
/** @var common\models\Post $post */
use yii\helpers\Html;
?>

<article class="popular-overall-item">
    <h6 class="popular-overall-title">
        <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
    </h6>
    <div class="post-meta-tiny">
        <small><i class="fas fa-eye"></i> <?= number_format($post->view_count) ?></small>
        <small class="ms-2"><?= date('m.d', $post->published_at) ?></small>
    </div>
</article>
