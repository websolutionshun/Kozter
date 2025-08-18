<?php

/** @var yii\web\View $this */
/** @var common\models\Post $featuredPost */
/** @var array $categorizedPosts */
/** @var common\models\Post[] $recentPosts */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'KözTér - Főoldal';
?>

<div class="homepage">
    <div class="container">
        <!-- Friss hírek banner -->
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-newspaper me-2"></i>
            <strong>Friss hírek:</strong> 
            <?php if ($featuredPost): ?>
                <?= Html::a(Html::encode($featuredPost->title), ['/post/view', 'slug' => $featuredPost->slug], ['class' => 'alert-link']) ?>
            <?php else: ?>
                Kövess minket a legfrissebb közéleti hírekért!
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <?php if ($featuredPost): ?>
        <!-- Kiemelt cikk -->
        <div class="featured-card p-4 mb-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="badge bg-warning text-dark mb-2">KIEMELT</div>
                    <h1 class="text-white mb-3"><?= Html::encode($featuredPost->title) ?></h1>
                    <p class="text-white-50 mb-3 fs-5"><?= Html::encode($featuredPost->getShortContent(200)) ?></p>
                    <div class="d-flex align-items-center text-white-50 mb-3">
                        <small class="me-3">
                            <i class="fas fa-user me-1"></i>
                            <?= Html::encode($featuredPost->author->username ?? 'Ismeretlen') ?>
                        </small>
                        <small class="me-3">
                            <i class="fas fa-calendar me-1"></i>
                            <?= date('Y.m.d H:i', $featuredPost->published_at) ?>
                        </small>
                        <small>
                            <i class="fas fa-eye me-1"></i>
                            <?= number_format($featuredPost->view_count) ?> megtekintés
                        </small>
                    </div>
                    <?= Html::a('Tovább olvasom', ['/post/view', 'slug' => $featuredPost->slug], ['class' => 'btn btn-kozter']) ?>
                </div>
                <?php if ($featuredPost->featuredImage): ?>
                <div class="col-lg-4">
                    <img src="<?= Html::encode($featuredPost->featuredImage->path) ?>" 
                         alt="<?= Html::encode($featuredPost->title) ?>" 
                         class="img-fluid rounded">
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Kategóriás tartalom -->
        <div class="row">
            <div class="col-lg-8">
                <?php foreach ($categorizedPosts as $categoryData): ?>
                    <?php $category = $categoryData['category']; $posts = $categoryData['posts']; ?>
                    <div class="category-section p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="category-title"><?= Html::encode($category->name) ?></h3>
                            <?= Html::a('Összes <i class="fas fa-arrow-right"></i>', ['/post/category', 'slug' => $category->slug], [
                                'class' => 'btn btn-sm btn-outline-primary',
                                'encode' => false
                            ]) ?>
                        </div>
                        
                        <div class="row">
                            <?php foreach ($posts as $index => $post): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="post-card p-3 h-100">
                                        <?php if ($index === 0): ?>
                                            <!-- Első bejegyzés nagyobb -->
                                            <h5 class="post-title mb-2">
                                                <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
                                            </h5>
                                            <p class="post-excerpt mb-2"><?= Html::encode($post->getShortContent(120)) ?></p>
                                        <?php else: ?>
                                            <!-- Többi bejegyzés kisebb -->
                                            <h6 class="post-title mb-2">
                                                <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
                                            </h6>
                                            <p class="post-excerpt mb-2 small"><?= Html::encode($post->getShortContent(80)) ?></p>
                                        <?php endif; ?>
                                        
                                        <div class="post-meta d-flex justify-content-between">
                                            <small><?= date('m.d H:i', $post->published_at) ?></small>
                                            <small><i class="fas fa-eye"></i> <?= number_format($post->view_count) ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Oldalsáv -->
            <div class="col-lg-4">
                <!-- Legfrissebb bejegyzések -->
                <div class="category-section p-4 mb-4">
                    <h4 class="category-title">Legfrissebb bejegyzések</h4>
                    
                    <?php foreach (array_slice($recentPosts, 0, 6) as $post): ?>
                        <div class="border-bottom pb-2 mb-2">
                            <h6 class="post-title mb-1">
                                <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
                            </h6>
                            <div class="post-meta">
                                <small><?= date('Y.m.d H:i', $post->published_at) ?></small>
                                <small class="ms-2"><i class="fas fa-eye"></i> <?= number_format($post->view_count) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="text-center mt-3">
                        <?= Html::a('Összes bejegyzés', ['/post/index'], ['class' => 'btn btn-kozter btn-sm']) ?>
                    </div>
                </div>

                <!-- Gyors linkek -->
                <div class="category-section p-4 mb-4">
                    <h4 class="category-title">Gyors linkek</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <?= Html::a('<i class="fas fa-microphone me-2"></i>Podcastok', ['/site/podcasts'], [
                                'class' => 'text-decoration-none',
                                'encode' => false
                            ]) ?>
                        </li>
                        <li class="mb-2">
                            <?= Html::a('<i class="fas fa-video me-2"></i>Videók', ['/site/videos'], [
                                'class' => 'text-decoration-none',
                                'encode' => false
                            ]) ?>
                        </li>
                        <li class="mb-2">
                            <?= Html::a('<i class="fas fa-heart me-2"></i>Támogatás', ['/site/support'], [
                                'class' => 'text-decoration-none',
                                'encode' => false
                            ]) ?>
                        </li>
                        <li class="mb-2">
                            <?= Html::a('<i class="fas fa-envelope me-2"></i>Kapcsolat', ['/site/contact'], [
                                'class' => 'text-decoration-none',
                                'encode' => false
                            ]) ?>
                        </li>
                    </ul>
                </div>

                <!-- Támogatás blokk -->
                <div class="text-center p-4" style="background: linear-gradient(135deg, var(--kozter-yellow) 0%, var(--kozter-yellow-light) 100%); border-radius: 12px;">
                    <h5 class="text-primary mb-3">Támogasd a munkánkat!</h5>
                    <p class="mb-3">Független újságírásunk fenntartásához szükségünk van a támogatásodra.</p>
                    <?= Html::a('Támogatok', ['/site/support'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome ikonok -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
