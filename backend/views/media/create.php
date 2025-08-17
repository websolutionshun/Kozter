<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Media */

$this->title = 'Média feltöltése';
?>

<div class="media-create">

    <div class="page-body">
        <div class="container-fluid">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Új média feltöltése</h3>
                        </div>
                        
                        <?php $form = ActiveForm::begin([
                            'options' => ['enctype' => 'multipart/form-data'],
                            'fieldConfig' => [
                                'template' => '<div class="mb-3">{label}{input}{error}</div>',
                                'labelOptions' => ['class' => 'form-label'],
                                'inputOptions' => ['class' => 'form-control'],
                                'errorOptions' => ['class' => 'invalid-feedback d-block'],
                            ],
                        ]); ?>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <?= $form->field($model, 'uploadedFile')->fileInput([
                                        'accept' => 'image/*,video/*',
                                        'class' => 'form-control'
                                    ])->label('Fájl kiválasztása') ?>

                                    <div class="mb-3">
                                        <label class="form-label">Megengedett formátumok</label>
                                        <div class="text-muted small">
                                            <strong>Képek:</strong> JPG, JPEG, PNG, GIF, WebP<br>
                                            <strong>Videók:</strong> MP4, AVI, MOV, WMV<br>
                                            <strong>Maximális fájlméret:</strong> 50MB
                                        </div>
                                    </div>

                                    <?= $form->field($model, 'alt_text')->textInput([
                                        'placeholder' => 'Alt szöveg képekhez (SEO és akadálymentesség)'
                                    ]) ?>

                                    <?= $form->field($model, 'description')->textarea([
                                        'rows' => 4,
                                        'placeholder' => 'Média leírása...'
                                    ]) ?>

                                    <?= $form->field($model, 'status')->dropDownList(
                                        \common\models\Media::getStatusOptions(),
                                        ['class' => 'form-select']
                                    ) ?>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Feltöltési tippek</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <h5 class="mb-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4z"/>
                                                        <path d="M3 6m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z"/>
                                                    </svg>
                                                    Képek
                                                </h5>
                                                <ul class="list-unstyled text-muted small">
                                                    <li>• Optimális méret: 1920x1080px</li>
                                                    <li>• Web-optimalizált formátum: WebP, JPG</li>
                                                    <li>• Tömörítsd a fájlokat</li>
                                                </ul>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <h5 class="mb-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4z"/>
                                                        <path d="M3 6m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z"/>
                                                    </svg>
                                                    Videók
                                                </h5>
                                                <ul class="list-unstyled text-muted small">
                                                    <li>• Ajánlott formátum: MP4</li>
                                                    <li>• Maximális hossz: 5 perc</li>
                                                    <li>• Tömörítsd nagy fájlokat</li>
                                                </ul>
                                            </div>
                                            
                                            <div class="alert alert-info">
                                                <h4 class="alert-title">Alt szöveg</h4>
                                                <div class="text-muted">Az alt szöveg segíti a látássérült felhasználókat és javítja a SEO-t.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <?= Html::a('Mégse', ['index'], ['class' => 'btn btn-outline-secondary me-2']) ?>
                            <?= Html::submitButton('Feltöltés', ['class' => 'btn btn-primary']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
