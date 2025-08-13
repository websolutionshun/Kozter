<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
/* @var $parentCategories array */

$this->title = 'Új kategória létrehozása';
$this->params['breadcrumbs'][] = ['label' => 'Kategóriák', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-create">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-folder-plus me-2"></i>
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
                                'placeholder' => 'Kategória neve',
                                'class' => 'form-control',
                                'autofocus' => true
                            ])->label('Kategória neve <span class="text-danger">*</span>') ?>
                            
                            <?= $form->field($model, 'slug')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'url-slug (automatikusan generálódik)',
                                'class' => 'form-control'
                            ])->hint('Ha üresen hagyod, automatikusan generálódik a név alapján.') ?>
                        </div>
                        
                        <div class="col-md-4">
                            <?= $form->field($model, 'parent_id')->dropDownList($parentCategories, [
                                'prompt' => 'Válassz szülő kategóriát...',
                                'class' => 'form-select'
                            ])->label('Szülő kategória') ?>
                            
                            <?= $form->field($model, 'status')->dropDownList(Category::getStatusOptions(), [
                                'class' => 'form-select'
                            ]) ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'description')->textarea([
                        'rows' => 4,
                        'placeholder' => 'Írj egy rövid leírást a kategóriáról...',
                        'class' => 'form-control'
                    ])->hint('A leírás segíthet a látogatóknak megérteni, hogy milyen tartalom tartozik ehhez a kategóriához.') ?>

                    <div class="form-footer">
                        <div class="btn-list">
                            <?= Html::a('Mégse', ['index'], ['class' => 'btn btn-secondary']) ?>
                            <?= Html::submitButton('<i class="ti ti-device-floppy me-2"></i>Kategória mentése', [
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
                                        <strong>Jó kategória név</strong>
                                    </div>
                                    <div class="text-muted">Használj rövid, leíró neveket</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm bg-info-lt">
                                        <i class="ti ti-link"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="text-truncate">
                                        <strong>URL slug</strong>
                                    </div>
                                    <div class="text-muted">Automatikusan generálódik</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item px-0">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm bg-success-lt">
                                        <i class="ti ti-hierarchy"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="text-truncate">
                                        <strong>Hierarchia</strong>
                                    </div>
                                    <div class="text-muted">Alkategóriákat is létrehozhatsz</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Meglévő kategóriák gyors áttekintése -->
            <?php if (!empty($parentCategories)): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Meglévő kategóriák</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach (array_slice($parentCategories, 1, 5, true) as $id => $name): ?>
                        <div class="list-group-item px-0 py-2">
                            <i class="ti ti-folder me-2 text-muted"></i>
                            <?= Html::encode($name) ?>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (count($parentCategories) > 6): ?>
                        <div class="list-group-item px-0 py-2 text-muted">
                            <i class="ti ti-dots me-2"></i>
                            és még <?= count($parentCategories) - 6 ?> kategória
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
$('#category-name').on('keyup', function() {
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
    
    if ($('#category-slug').val() === '' || $('#category-slug').data('auto') !== false) {
        $('#category-slug').val(slug).data('auto', true);
    }
});

// Disable auto-generation if user manually edits slug
$('#category-slug').on('keyup', function() {
    $(this).data('auto', false);
});
");
?>
