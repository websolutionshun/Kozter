<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Media;

/* @var $this yii\web\View */
/* @var $model common\models\Media */

$this->title = $model->original_name;
?>

<div class="media-view">
    <div class="page-header d-print-none">
        <div class="container-fluid">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Média részletei
                    </div>
                    <h2 class="page-title">
                        <?= Html::encode($this->title) ?>
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <?= Html::a('Szerkesztés', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Törlés', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-outline-danger',
                            'data' => [
                                'confirm' => 'Biztosan törölni szeretnéd ezt a médiát?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-fluid">
            <div class="row row-deck row-cards">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Média előnézet</h3>
                        </div>
                        <div class="card-body text-center">
                            <?php if ($model->media_type === Media::TYPE_IMAGE): ?>
                                <?= Html::img($model->getFileUrl(), [
                                    'class' => 'img-fluid rounded',
                                    'style' => 'max-height: 500px; width: auto;'
                                ]) ?>
                            <?php elseif ($model->media_type === Media::TYPE_VIDEO): ?>
                                <video controls class="img-fluid rounded" style="max-height: 500px; width: auto;">
                                    <source src="<?= $model->getFileUrl() ?>" type="<?= $model->mime_type ?>">
                                    A böngésződ nem támogatja a videó lejátszást.
                                </video>
                            <?php else: ?>
                                <div class="empty">
                                    <div class="empty-img">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="128" height="128" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="empty-title">Nincs előnézet</p>
                                    <p class="empty-subtitle text-muted">
                                        Ez a fájltípus nem jeleníthető meg előnézetként.
                                    </p>
                                    <div class="empty-action">
                                        <?= Html::a('Fájl letöltése', $model->getFileUrl(), [
                                            'class' => 'btn btn-primary',
                                            'download' => $model->original_name
                                        ]) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($model->description): ?>
                        <div class="card-footer">
                            <div class="text-muted">
                                <strong>Leírás:</strong><br>
                                <?= Html::encode($model->description) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Fájl információk</h3>
                        </div>
                        
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-vcenter card-table'],
                            'attributes' => [
                                [
                                    'attribute' => 'original_name',
                                    'label' => 'Eredeti név',
                                ],
                                [
                                    'attribute' => 'media_type',
                                    'label' => 'Típus',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        $colors = [
                                            Media::TYPE_IMAGE => 'success',
                                            Media::TYPE_VIDEO => 'primary',
                                            Media::TYPE_AUDIO => 'warning',
                                            Media::TYPE_DOCUMENT => 'info',
                                            Media::TYPE_OTHER => 'secondary',
                                        ];
                                        $color = $colors[$model->media_type] ?? 'secondary';
                                        return '<span class="badge bg-' . $color . '-lt">' . $model->getMediaTypeName() . '</span>';
                                    }
                                ],
                                [
                                    'attribute' => 'mime_type',
                                    'label' => 'MIME típus',
                                ],
                                [
                                    'attribute' => 'file_size',
                                    'label' => 'Fájlméret',
                                    'value' => $model->getHumanFileSize(),
                                ],
                                [
                                    'attribute' => 'width',
                                    'label' => 'Szélesség',
                                    'value' => $model->width ? $model->width . ' px' : '—',
                                ],
                                [
                                    'attribute' => 'height',
                                    'label' => 'Magasság',
                                    'value' => $model->height ? $model->height . ' px' : '—',
                                ],
                                [
                                    'attribute' => 'duration',
                                    'label' => 'Időtartam',
                                    'value' => $model->duration ? gmdate("H:i:s", $model->duration) : '—',
                                ],
                                [
                                    'attribute' => 'status',
                                    'label' => 'Állapot',
                                    'format' => 'raw',
                                    'value' => $model->status === Media::STATUS_ACTIVE 
                                        ? '<span class="badge bg-success-lt">Aktív</span>'
                                        : '<span class="badge bg-secondary-lt">Inaktív</span>',
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'label' => 'Feltöltve',
                                    'format' => ['datetime', 'php:Y.m.d H:i:s'],
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'label' => 'Módosítva',
                                    'format' => ['datetime', 'php:Y.m.d H:i:s'],
                                ],
                            ],
                        ]) ?>
                    </div>
                    
                    <?php if ($model->alt_text): ?>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">SEO információk</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Alt szöveg</label>
                                <div class="text-muted"><?= Html::encode($model->alt_text) ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Műveletek</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <?= Html::a('
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                                        <path d="M7 11l5 5l5 -5"/>
                                        <path d="M12 4l0 12"/>
                                    </svg>
                                    Letöltés
                                ', $model->getFileUrl(), [
                                    'class' => 'btn btn-outline-primary',
                                    'download' => $model->original_name
                                ]) ?>
                                
                                <button type="button" class="btn btn-outline-secondary" onclick="copyToClipboard('<?= $model->getFileUrl() ?>')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/>
                                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"/>
                                    </svg>
                                    URL másolása
                                </button>
                                
                                <?= Html::a('
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                    </svg>
                                    Új ablakban
                                ', $model->getFileUrl(), [
                                    'class' => 'btn btn-outline-info',
                                    'target' => '_blank'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Success feedback
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>Másolva!';
        btn.classList.add('btn-success');
        btn.classList.remove('btn-outline-secondary');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    }, function(err) {
        console.error('Nem sikerült a vágólapra másolni: ', err);
    });
}
</script>
