<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Tag;

/* @var $this yii\web\View */
/* @var $model common\models\Tag */

$this->title = 'Címke szerkesztése: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Címkék', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Szerkesztés: ' . $model->name;
?>

<div class="tag-update">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-edit me-2"></i>
                        Címke szerkesztése
                    </h3>
                    <div class="card-actions">
                        <?= Html::a('<i class="ti ti-eye me-1"></i>Megtekintés', ['index'], [
                            'class' => 'btn btn-outline-primary btn-sm'
                        ]) ?>
                    </div>
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
                                'placeholder' => 'url-slug',
                                'class' => 'form-control'
                            ])->hint('Az URL slug megváltoztatása hatással lehet a meglévő linkekre.') ?>
                        </div>
                        
                        <div class="col-md-4">
                            <?= $form->field($model, 'color')->input('color', [
                                'class' => 'form-control form-control-color'
                            ])->label('Színkód')->hint('Jelenlegi szín: ' . Html::tag('span', $model->color, [
                                'style' => 'display: inline-block; width: 20px; height: 20px; background-color: ' . $model->color . '; border: 1px solid #dee2e6; border-radius: 3px; vertical-align: middle; margin-left: 5px;'
                            ])) ?>
                            
                            <?= $form->field($model, 'status')->dropDownList(Tag::getStatusOptions(), [
                                'class' => 'form-select'
                            ]) ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'description')->textarea([
                        'rows' => 4,
                        'placeholder' => 'Írj egy rövid leírást a címkéről...',
                        'class' => 'form-control'
                    ]) ?>

                    <div class="form-footer">
                        <div class="btn-list">
                            <?= Html::a('Mégse', ['index'], ['class' => 'btn btn-secondary']) ?>
                            <?= Html::submitButton('<i class="ti ti-device-floppy me-2"></i>Változások mentése', [
                                'class' => 'btn btn-primary'
                            ]) ?>
                            <?= Html::a('<i class="ti ti-trash me-2"></i>Törlés', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-outline-danger',
                                'data' => [
                                    'confirm' => 'Biztosan törölni szeretnéd ezt a címkét?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Címke információk -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Címke információk</h3>
                </div>
                <div class="card-body">
                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">ID</div>
                            <div class="datagrid-content"><?= $model->id ?></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Létrehozva</div>
                            <div class="datagrid-content"><?= Yii::$app->formatter->asDatetime($model->created_at) ?></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Utoljára frissítve</div>
                            <div class="datagrid-content"><?= Yii::$app->formatter->asDatetime($model->updated_at) ?></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Elemek száma</div>
                            <div class="datagrid-content">
                                <span class="badge bg-blue-lt text-blue"><?= $model->count ?></span>
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Aktuális szín</div>
                            <div class="datagrid-content">
                                <div class="d-flex align-items-center">
                                    <div style="width: 24px; height: 24px; border-radius: 6px; background-color: <?= Html::encode($model->color) ?>; border: 1px solid #dee2e6; margin-right: 8px;"></div>
                                    <code><?= Html::encode($model->color) ?></code>
                                </div>
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">URL slug</div>
                            <div class="datagrid-content">
                                <code><?= Html::encode($model->slug) ?></code>
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
                                        class="btn p-0 border color-picker-btn <?= $model->color === $color ? 'border-primary border-3' : '' ?>" 
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
            <!-- Hasonló címkék -->
            <?php
            $similarTags = Tag::find()
                ->where(['!=', 'id', $model->id])
                ->andWhere(['status' => Tag::STATUS_ACTIVE])
                ->orderBy('created_at DESC')
                ->limit(5)
                ->all();
            if (!empty($similarTags)): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Más címkék</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($similarTags as $tag): ?>
                        <div class="list-group-item px-0 py-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div style="width: 16px; height: 16px; border-radius: 3px; background-color: <?= Html::encode($tag->color) ?>; border: 1px solid #dee2e6;"></div>
                                </div>
                                <div class="col">
                                    <?= Html::a($tag->name, ['update', 'id' => $tag->id], [
                                        'class' => 'text-decoration-none'
                                    ]) ?>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-<?= $tag->status == Tag::STATUS_ACTIVE ? 'green-lt text-green' : 'gray-lt text-gray' ?>">
                                        <?= $tag->getStatusName() ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$this->registerJs("
// Slug generation (only if manually changed)
var originalSlug = '" . Html::encode($model->slug) . "';
var manualSlugEdit = false;

$('#tag-name').on('keyup', function() {
    if (!manualSlugEdit) {
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
        
        $('#tag-slug').val(slug);
    }
});

$('#tag-slug').on('keyup', function() {
    if ($(this).val() !== originalSlug) {
        manualSlugEdit = true;
    }
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

// Listen for manual color input changes
$('#tag-color').on('change', function() {
    var color = this.value;
    $('.color-picker-btn').removeClass('border-primary border-3');
    $('.color-picker-btn[data-color=\"' + color + '\"]').addClass('border-primary border-3');
});

// Toggle status function
function toggleStatus(id) {
    $.post('" . \yii\helpers\Url::to(['tag/toggle-status']) . "', {
        id: id,
        _csrf: '" . Yii::$app->request->csrfToken . "'
    }).done(function(data) {
        if (data.success) {
            location.reload();
        } else {
            alert('Hiba: ' + data.message);
        }
    });
}
");
?>