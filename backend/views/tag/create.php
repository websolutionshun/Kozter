<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Tag;

/* @var $this yii\web\View */
/* @var $model common\models\Tag */

$this->title = 'Új címke létrehozása';
$this->params['breadcrumbs'][] = ['label' => 'Címkék', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tag-create">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-tag me-2"></i>
                        <?= Html::encode($this->title) ?>
                    </h3>
                </div>
                
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "{label}\n{input}\n{hint}\n{error}",
                            'labelOptions' => ['class' => 'form-label'],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col-md-8">
                            <?= $form->field($model, 'name')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'Címke neve',
                                'class' => 'form-control',
                                'autofocus' => true
                            ])->label('Címke neve <span class="text-danger">*</span>') ?>
                            
                            <?= $form->field($model, 'slug')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'url-slug (automatikusan generálódik)',
                                'class' => 'form-control'
                            ])->hint('Ha üresen hagyod, automatikusan generálódik a név alapján.') ?>
                        </div>
                        
                        <div class="col-md-4">
                            <?= $form->field($model, 'color')->input('color', [
                                'class' => 'form-control form-control-color',
                                'value' => $model->color ?: '#007acc'
                            ])->label('Színkód') ?>
                            
                            <?= $form->field($model, 'status')->dropDownList(Tag::getStatusOptions(), [
                                'class' => 'form-select'
                            ]) ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'description')->textarea([
                        'rows' => 4,
                        'placeholder' => 'Írj egy rövid leírást a címkéről...',
                        'class' => 'form-control'
                    ])->hint('A leírás segíthet megérteni, hogy mikor használd ezt a címkét.') ?>

                    <div class="form-footer">
                        <div class="btn-list">
                            <?= Html::a('Mégse', ['index'], ['class' => 'btn btn-secondary']) ?>
                            <?= Html::submitButton('<i class="ti ti-device-floppy me-2"></i>Címke mentése', [
                                'class' => 'btn btn-primary'
                            ]) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tippek</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm bg-primary-lt">
                                        <i class="ti ti-bulb"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="text-truncate">
                                        <strong>Jó címke név</strong>
                                    </div>
                                    <div class="text-muted">Használj rövid, specifikus neveket</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm bg-info-lt">
                                        <i class="ti ti-palette"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="text-truncate">
                                        <strong>Színkód</strong>
                                    </div>
                                    <div class="text-muted">Válassz egyedi színt minden címkéhez</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm bg-success-lt">
                                        <i class="ti ti-tags"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="text-truncate">
                                        <strong>Konzisztencia</strong>
                                    </div>
                                    <div class="text-muted">Használj egységes elnevezést</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Színpaletta -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Színpaletta</h3>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <?php foreach (Tag::getDefaultColors() as $color): ?>
                            <div class="col-auto">
                                <button type="button" 
                                        class="btn p-0 border color-picker-btn" 
                                        style="width: 32px; height: 32px; background-color: <?= $color ?>; border-radius: 6px;"
                                        data-color="<?= $color ?>"
                                        title="<?= $color ?>">
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-text mt-2">Kattints egy színre a gyors kiválasztáshoz</div>
                    
                    <div class="mt-3">
                        <button type="button" id="random-color-btn" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="ti ti-refresh me-2"></i>Véletlenszerű szín
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Meglévő címkék gyors áttekintése -->
            <?php
            $existingTags = Tag::find()->where(['status' => Tag::STATUS_ACTIVE])->orderBy('created_at DESC')->limit(5)->all();
            if (!empty($existingTags)): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Legújabb címkék</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($existingTags as $tag): ?>
                        <div class="list-group-item px-0 py-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div style="width: 16px; height: 16px; border-radius: 3px; background-color: <?= Html::encode($tag->color) ?>; border: 1px solid #dee2e6;"></div>
                                </div>
                                <div class="col">
                                    <?= Html::encode($tag->name) ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (Tag::find()->count() > 5): ?>
                        <div class="list-group-item px-0 py-2 text-muted">
                            <i class="ti ti-dots me-2"></i>
                            és még <?= Tag::find()->count() - 5 ?> címke
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$this->registerJs("
// Auto-generate slug from name
$('#tag-name').on('keyup', function() {
    var name = $(this).val();
    var slug = name.toLowerCase()
        .replace(/[áàâä]/g, 'a')
        .replace(/[éèêë]/g, 'e')
        .replace(/[íìîï]/g, 'i')
        .replace(/[óòôö]/g, 'o')
        .replace(/[úùûü]/g, 'u')
        .replace(/[ő]/g, 'o')
        .replace(/[ű]/g, 'u')
        .replace(/[ç]/g, 'c')
        .replace(/[ñ]/g, 'n')
        .replace(/[^a-z0-9]/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
    
    if ($('#tag-slug').val() === '' || $('#tag-slug').data('auto') !== false) {
        $('#tag-slug').val(slug).data('auto', true);
    }
});

// Disable auto-generation if user manually edits slug
$('#tag-slug').on('keyup', function() {
    $(this).data('auto', false);
});

// Color picker functionality
$(document).on('click', '.color-picker-btn', function() {
    var color = $(this).data('color');
    $('#tag-color').val(color);
    
    // Remove active class from all
    $('.color-picker-btn').removeClass('border-primary border-3');
    // Add active class to clicked
    $(this).addClass('border-primary border-3');
});

// Random color generator
$('#random-color-btn').on('click', function() {
    var colors = " . \yii\helpers\Json::encode(Tag::getDefaultColors()) . ";
    var randomColor = colors[Math.floor(Math.random() * colors.length)];
    $('#tag-color').val(randomColor);
    
    // Update active color picker
    $('.color-picker-btn').removeClass('border-primary border-3');
    $('.color-picker-btn[data-color=\"' + randomColor + '\"]').addClass('border-primary border-3');
});

// Set initial active color
$(document).ready(function() {
    var currentColor = $('#tag-color').val();
    $('.color-picker-btn[data-color=\"' + currentColor + '\"]').addClass('border-primary border-3');
});
");
?>