<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Media;

/* @var $this yii\web\View */
/* @var $model common\models\Media */

$this->title = 'Média szerkesztése: ' . $model->original_name;
?>

<div class="media-update">

    <div class="page-body">
        <div class="container-fluid">
            <!-- Szerkesztő form - teljes szélességű -->
            <div class="row row-deck row-cards mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Média adatok szerkesztése</h3>
                        </div>
                        
                        <?php $form = ActiveForm::begin([
                            'fieldConfig' => [
                                'template' => '<div class="mb-3">{label}{input}{error}</div>',
                                'labelOptions' => ['class' => 'form-label'],
                                'inputOptions' => ['class' => 'form-control'],
                                'errorOptions' => ['class' => 'invalid-feedback d-block'],
                            ],
                        ]); ?>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <?= $form->field($model, 'alt_text')->textInput([
                                        'placeholder' => 'Alt szöveg képekhez (SEO és akadálymentesség)'
                                    ]) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'description')->textarea([
                                        'rows' => 4,
                                        'placeholder' => 'Média leírása...'
                                    ]) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'status')->dropDownList(
                                        Media::getStatusOptions(),
                                        ['class' => 'form-select']
                                    ) ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <?= Html::a('Mégse', ['view', 'id' => $model->id], ['class' => 'btn btn-outline-secondary me-2']) ?>
                            <?= Html::submitButton('Mentés', ['class' => 'btn btn-primary']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
            
            <!-- Alsó sor: Média előnézet és Hasznos tippek -->
            <div class="row row-deck row-cards">
                <!-- Média előnézet és adatok oszlop -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Jelenlegi média</h3>
                        </div>
                        <div class="card-body text-center">
                            <?php if ($model->media_type === Media::TYPE_IMAGE): ?>
                                <?= Html::img($model->getFileUrl(), [
                                    'class' => 'img-fluid rounded',
                                    'style' => 'max-height: 300px; width: auto;'
                                ]) ?>
                            <?php elseif ($model->media_type === Media::TYPE_VIDEO): ?>
                                <video controls class="img-fluid rounded" style="max-height: 300px; width: auto;">
                                    <source src="<?= $model->getFileUrl() ?>" type="<?= $model->mime_type ?>">
                                    A böngésződ nem támogatja a videó lejátszást.
                                </video>
                            <?php else: ?>
                                <div class="empty">
                                    <div class="empty-img">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="96" height="96" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="empty-title"><?= Html::encode($model->original_name) ?></p>
                                    <p class="empty-subtitle text-muted">
                                        <?= $model->getMediaTypeName() ?> • <?= $model->getHumanFileSize() ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Fájl adatok közvetlenül az előnézet alatt -->
                        <div class="card-body border-top">
                            <h5 class="card-title mb-3">Fájl adatok</h5>
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <div class="text-muted small">Eredeti név</div>
                                    <div class="fw-medium"><?= Html::encode($model->original_name) ?></div>
                                </div>
                                <div class="col-6 mb-2">
                                    <div class="text-muted small">Méret</div>
                                    <div class="fw-medium"><?= $model->getHumanFileSize() ?></div>
                                </div>
                                <div class="col-6 mb-2">
                                    <div class="text-muted small">Típus</div>
                                    <div class="fw-medium"><?= $model->getMediaTypeName() ?></div>
                                </div>
                                <div class="col-6 mb-2">
                                    <div class="text-muted small">MIME</div>
                                    <div class="fw-medium"><small><?= Html::encode($model->mime_type) ?></small></div>
                                </div>
                                <?php if ($model->width && $model->height): ?>
                                <div class="col-6 mb-2">
                                    <div class="text-muted small">Felbontás</div>
                                    <div class="fw-medium"><?= $model->width ?> × <?= $model->height ?> px</div>
                                </div>
                                <?php endif; ?>
                                <?php if ($model->duration): ?>
                                <div class="col-6 mb-2">
                                    <div class="text-muted small">Időtartam</div>
                                    <div class="fw-medium"><?= gmdate("H:i:s", $model->duration) ?></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Hasznos tippek oszlop -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Hasznos tippek</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-3">
                                <h4 class="alert-title">Alt szöveg</h4>
                                <div class="text-muted">Az alt szöveg fontos a látássérült felhasználók számára és javítja a SEO rangsorolást.</div>
                            </div>
                            
                            <div class="alert alert-warning mb-0">
                                <h4 class="alert-title">Leírás</h4>
                                <div class="text-muted">A részletes leírás segít a média későbbi megtalálásában és használatában.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
