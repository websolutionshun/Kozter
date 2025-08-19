<?php
/** @var common\models\Post $post */
use yii\helpers\Html;
?>

<article class="news-item">
    <h6 class="news-title">
        <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
    </h6>
    <div class="post-meta-tiny">
        <small><?= date('m.d H:i', $post->published_at) ?></small>
        <small class="ms-2"><i class="fas fa-eye"></i> <?= $post->view_count ?></small>
    </div>
</article>
