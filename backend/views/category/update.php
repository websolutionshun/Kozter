<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
/* @var $parentCategories array */

$this->title = 'Kategória szerkesztése: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Kategóriák', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Szerkesztés: ' . $model->name;
?>

<div class="category-update">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="ti ti-edit me-2"></i>
                        Kategória szerkesztése
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
                                'placeholder' => 'Kategória neve',
                                'class' => 'form-control',
                                'autofocus' => true
                            ])->label('Kategória neve <span class="text-danger">*</span>') ?>
                            
                            <?= $form->field($model, 'slug')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'url-slug',
                                'class' => 'form-control'
                            ])->hint('Az URL slug megváltoztatása hatással lehet a meglévő linkekre.') ?>
                        </div>
                        
                        <div class="col-md-4">
                            <?= $form->field($model, 'parent_id')->dropDownList($parentCategories, [
                                'prompt' => 'Nincs szülő kategória',
                                'class' => 'form-select'
                            ])->label('Szülő kategória')->hint('A kategória nem lehet saját magának vagy leszármazottjának szülője.') ?>
                            
                            <?= $form->field($model, 'status')->dropDownList(Category::getStatusOptions(), [
                                'class' => 'form-select'
                            ]) ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'description')->textarea([
                        'rows' => 4,
                        'placeholder' => 'Írj egy rövid leírást a kategóriáról...',
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
                                    'confirm' => 'Biztosan törölni szeretnéd ezt a kategóriát?',
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
            <!-- Kategória információk -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kategória információk</h3>
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
                        <?php if ($model->parent): ?>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Szülő kategória</div>
                            <div class="datagrid-content">
                                <?= Html::a($model->parent->name, ['update', 'id' => $model->parent->id], [
                                    'class' => 'text-decoration-none'
                                ]) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Teljes útvonal</div>
                            <div class="datagrid-content">
                                <small class="text-muted"><?= Html::encode($model->getFullPath()) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gyerek kategóriák -->
            <?php if (!empty($model->children)): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Alkategóriák</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($model->children as $child): ?>
                        <div class="list-group-item px-0 py-2">
                            <div class="row align-items-center">
                                <div class="col">
                                    <i class="ti ti-folder me-2 text-muted"></i>
                                    <?= Html::a($child->name, ['update', 'id' => $child->id], [
                                        'class' => 'text-decoration-none'
                                    ]) ?>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-<?= $child->status == Category::STATUS_ACTIVE ? 'green-lt text-green' : 'gray-lt text-gray' ?>">
                                        <?= $child->getStatusName() ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Műveletek -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Műveletek</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <?= Html::a('<i class="ti ti-plus me-2"></i>Új alkategória létrehozása', ['create', 'parent_id' => $model->id], [
                                'class' => 'btn btn-outline-primary btn-sm w-100'
                            ]) ?>
                        </div>
                        
                        <div class="list-group-item px-0">
                            <?= Html::a('<i class="ti ti-copy me-2"></i>Kategória duplikálása', ['duplicate', 'id' => $model->id], [
                                'class' => 'btn btn-outline-secondary btn-sm w-100'
                            ]) ?>
                        </div>
                        
                        <div class="list-group-item px-0">
                            <button onclick="toggleStatus(<?= $model->id ?>)" class="btn btn-outline-info btn-sm w-100">
                                <i class="ti ti-toggle-<?= $model->status == Category::STATUS_ACTIVE ? 'right' : 'left' ?> me-2"></i>
                                Állapot váltása
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs("
// Slug generation (only if manually changed)
var originalSlug = '" . Html::encode($model->slug) . "';
var manualSlugEdit = false;

$('#category-name').on('keyup', function() {
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
        
        $('#category-slug').val(slug);
    }
});

$('#category-slug').on('keyup', function() {
    if ($(this).val() !== originalSlug) {
        manualSlugEdit = true;
    }
});

// Toggle status function
function toggleStatus(id) {
    $.post('" . \yii\helpers\Url::to(['category/toggle-status']) . "', {
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
