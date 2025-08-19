<?php
/** @var common\models\Post $post */
use yii\helpers\Html;
?>

<article class="category-item">
    <h6 class="category-item-title">
        <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
    </h6>
    <div class="post-meta-tiny">
        <small><?= date('m.d H:i', $post->published_at) ?></small>
    </div>
</article>
