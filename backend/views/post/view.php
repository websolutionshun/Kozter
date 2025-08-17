<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Post;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Bejegyzések', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-view">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Bejegyzés részletei</h3>
                    <div class="btn-group">
                        <?= Html::a('Szerkesztés', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                        <?= Html::a('Törlés', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-outline-danger btn-sm',
                            'data' => [
                                'confirm' => 'Biztosan törölni szeretnéd ezt a bejegyzést?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Cím és alapadatok -->
                    <div class="mb-4">
                        <h1 class="display-6"><?= Html::encode($model->title) ?></h1>
                        <div class="text-muted">
                            <small>
                                <strong>Slug:</strong> <code><?= Html::encode($model->slug) ?></code> |
                                <strong>ID:</strong> <?= $model->id ?> |
                                <strong>Szerző:</strong> <?= Html::encode($model->author->username ?? 'N/A') ?>
                            </small>
                        </div>
                    </div>

                    <!-- Kiemelt kép -->
                    <?php if ($model->featuredImage): ?>
                        <div class="mb-4">
                            <h5>Kiemelt kép</h5>
                            <img src="<?= $model->featuredImage->getFileUrl() ?>" 
                                 alt="<?= Html::encode($model->featuredImage->alt_text) ?>" 
                                 class="img-fluid rounded" 
                                 style="max-height: 300px;">
                        </div>
                    <?php endif; ?>

                    <!-- Kivonat -->
                    <?php if ($model->excerpt): ?>
                        <div class="mb-4">
                            <h5>Kivonat</h5>
                            <div class="alert alert-info">
                                <?= nl2br(Html::encode($model->excerpt)) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Tartalom -->
                    <div class="mb-4">
                        <h5>Tartalom</h5>
                        <div class="border rounded p-3">
                            <?= $model->content ?>
                        </div>
                    </div>

                    <!-- Kategóriák és címkék -->
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Kategóriák</h6>
                            <?php if (!empty($model->categories)): ?>
                                <?php foreach ($model->categories as $category): ?>
                                    <span class="badge bg-blue-lt text-blue me-1"><?= Html::encode($category->name) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted">Nincsenek kategóriák</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h6>Címkék</h6>
                            <?php if (!empty($model->tags)): ?>
                                <?php foreach ($model->tags as $tag): ?>
                                    <span class="badge bg-gray-lt text-gray me-1"><?= Html::encode($tag->name) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted">Nincsenek címkék</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Alapadatok -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">Alapadatok</h4>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped detail-view mb-0'],
                        'attributes' => [
                            [
                                'attribute' => 'status',
                                'value' => function ($model) {
                                    $statusClass = $model->status == Post::STATUS_PUBLISHED ? 'success' : 
                                                  ($model->status == Post::STATUS_DRAFT ? 'secondary' : 'danger');
                                    return '<span class="badge bg-' . $statusClass . '">' . $model->getStatusName() . '</span>';
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'visibility',
                                'value' => function ($model) {
                                    $visibilityClass = $model->visibility == Post::VISIBILITY_PUBLIC ? 'success' : 'warning';
                                    return '<span class="badge bg-' . $visibilityClass . '">' . $model->getVisibilityName() . '</span>';
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'comment_status',
                                'value' => function ($model) {
                                    $commentClass = $model->comment_status == Post::COMMENT_ENABLED ? 'success' : 'secondary';
                                    return '<span class="badge bg-' . $commentClass . '">' . $model->getCommentStatusName() . '</span>';
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'view_count',
                                'value' => number_format($model->view_count),
                            ],
                        ],
                    ]) ?>
                </div>
            </div>

            <!-- Dátumok -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">Dátumok</h4>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped detail-view mb-0'],
                        'attributes' => [
                            [
                                'attribute' => 'created_at',
                                'value' => date('Y-m-d H:i:s', $model->created_at),
                            ],
                            [
                                'attribute' => 'updated_at',
                                'value' => date('Y-m-d H:i:s', $model->updated_at),
                            ],
                            [
                                'attribute' => 'published_at',
                                'value' => $model->published_at ? date('Y-m-d H:i:s', $model->published_at) : 'Nem publikált',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>

            <!-- SEO adatok -->
            <?php if ($model->seo_title || $model->seo_description || $model->seo_keywords): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title">SEO beállítások</h4>
                    </div>
                    <div class="card-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-striped detail-view mb-0'],
                            'attributes' => [
                                [
                                    'attribute' => 'seo_title',
                                    'value' => $model->seo_title ?: '<span class="text-muted">Nincs beállítva</span>',
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'seo_description',
                                    'value' => $model->seo_description ? nl2br(Html::encode($model->seo_description)) : '<span class="text-muted">Nincs beállítva</span>',
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'seo_keywords',
                                    'value' => $model->seo_keywords ?: '<span class="text-muted">Nincsenek beállítva</span>',
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'seo_robots',
                                    'value' => '<code>' . Html::encode($model->seo_robots) . '</code>',
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'seo_canonical_url',
                                    'value' => $model->seo_canonical_url ? Html::a($model->seo_canonical_url, $model->seo_canonical_url, ['target' => '_blank']) : '<span class="text-muted">Nincs beállítva</span>',
                                    'format' => 'raw',
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Gyors műveletek -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Gyors műveletek</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if ($model->status == Post::STATUS_PUBLISHED): ?>
                            <button type="button" onclick="toggleStatus(<?= $model->id ?>)" class="btn btn-outline-secondary btn-sm">
                                Visszavonás vázlatba
                            </button>
                        <?php else: ?>
                            <button type="button" onclick="toggleStatus(<?= $model->id ?>)" class="btn btn-outline-success btn-sm">
                                Közzététel
                            </button>
                        <?php endif; ?>
                        
                        <?= Html::a('Másik bejegyzés létrehozása', ['create'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                        
                        <a href="#" class="btn btn-outline-info btn-sm" target="_blank">
                            Előnézet megtekintése
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle status funkció
function toggleStatus(id) {
    if (confirm('Biztosan módosítani szeretnéd a bejegyzés állapotát?')) {
        $.ajax({
            url: '<?= \yii\helpers\Url::to(['post/toggle-status', 'id' => '']) ?>' + id,
            type: 'POST',
            data: {
                '_csrf': '<?= Yii::$app->request->csrfToken ?>'
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Hiba: ' + (data.message || 'Ismeretlen hiba történt'));
                }
            },
            error: function(xhr, status, error) {
                alert('Hiba az állapot módosítása során: ' + error);
            }
        });
    }
}
</script>
