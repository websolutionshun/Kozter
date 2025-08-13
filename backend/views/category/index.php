<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parentCategories array */

$this->title = 'Kategóriák kezelése';
$this->params['breadcrumbs'][] = 'Kategóriák';

// JavaScript a WordPress stílusú működéshez
$this->registerJs("
// Bulk actions
$('#bulk-action-form').on('submit', function(e) {
    e.preventDefault();
    var action = $('#bulk-action-selector').val();
    var selected = $('input.select-row:checked');
    
    if (selected.length === 0) {
        alert('Válassz ki legalább egy kategóriát.');
        return false;
    }
    
    if (action === 'delete') {
        if (!confirm('Biztosan törölni szeretnéd a kiválasztott kategóriákat?')) {
            return false;
        }
        
        var form = $('<form method=\"post\" action=\"' + '" . Url::to(['category/bulk-delete']) . "' + '\"></form>');
        selected.each(function() {
            form.append('<input type=\"hidden\" name=\"selection[]\" value=\"' + $(this).val() + '\">');
        });
        form.append('" . Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) . "');
        $('body').append(form);
        form.submit();
    }
});

// Select all checkbox
$('#select-all').change(function() {
    $('input.select-row').prop('checked', $(this).prop('checked'));
});

// Quick edit
function quickEdit(id) {
    var row = $('#category-' + id);
    var name = row.find('.category-name').text();
    var slug = row.find('.category-slug').text();
    
    row.find('.category-name').html('<input type=\"text\" class=\"form-control form-control-sm\" id=\"quick-name-' + id + '\" value=\"' + name + '\">');
    row.find('.category-slug').html('<input type=\"text\" class=\"form-control form-control-sm\" id=\"quick-slug-' + id + '\" value=\"' + slug + '\">');
    row.find('.quick-edit-btn').hide();
    row.find('.quick-save-btn, .quick-cancel-btn').show();
}

function cancelQuickEdit(id) {
    location.reload(); // Egyszerű megoldás
}

function saveQuickEdit(id) {
    var name = $('#quick-name-' + id).val();
    var slug = $('#quick-slug-' + id).val();
    
    $.post('" . Url::to(['category/quick-edit']) . "', {
        id: id,
        name: name,
        slug: slug,
        _csrf: '" . Yii::$app->request->csrfToken . "'
    }).done(function(data) {
        if (data.success) {
            location.reload();
        } else {
            alert('Hiba: ' + data.message);
        }
    });
}

// Toggle status
function toggleStatus(id) {
    $.post('" . Url::to(['category/toggle-status']) . "', {
        id: id,
        _csrf: '" . Yii::$app->request->csrfToken . "'
    }).done(function(data) {
        if (data.success) {
            var statusCell = $('#category-' + id).find('.status-cell');
            statusCell.html('<span class=\"badge bg-' + (data.status == 1 ? 'green-lt text-green' : 'gray-lt text-gray') + '\">' + data.statusName + '</span>');
        } else {
            alert('Hiba: ' + data.message);
        }
    });
}
");
?>

<div class="category-index">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Kategóriák</h3>
                    <div>
                        <?= Html::a('Új kategória', ['create'], ['class' => 'btn btn-primary btn-sm']) ?>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <!-- Bulk actions -->
                    <div class="p-3 border-bottom">
                        <?= Html::beginForm('', 'post', ['id' => 'bulk-action-form', 'class' => 'd-flex align-items-center gap-2']) ?>
                            <select name="action" id="bulk-action-selector" class="form-select form-select-sm" style="width: auto;">
                                <option value="">Tömeges műveletek</option>
                                <option value="delete">Törlés</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Alkalmaz</button>
                        <?= Html::endForm() ?>
                    </div>

                    <?php Pjax::begin(['id' => 'category-pjax']); ?>
                    
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">
                                        <input type="checkbox" id="select-all" class="form-check-input">
                                    </th>
                                    <th>Név</th>
                                    <th>Slug</th>
                                    <th>Szülő</th>
                                    <th style="width: 100px;">Állapot</th>
                                    <th style="width: 80px;" class="text-center">Elemek</th>
                                    <th style="width: 120px;" class="text-center">Műveletek</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataProvider->getModels() as $model): ?>
                                <tr id="category-<?= $model->id ?>">
                                    <td>
                                        <input type="checkbox" name="selection[]" value="<?= $model->id ?>" class="form-check-input select-row">
                                    </td>
                                    <td>
                                        <div class="category-name fw-semibold"><?= Html::encode($model->name) ?></div>
                                        <div class="text-muted small">
                                            ID: <?= $model->id ?> | 
                                            <a href="javascript:void(0)" onclick="quickEdit(<?= $model->id ?>)" class="quick-edit-btn text-decoration-none">Gyors szerkesztés</a>
                                            <button onclick="saveQuickEdit(<?= $model->id ?>)" class="btn btn-link btn-sm p-0 quick-save-btn text-success" style="display: none;">Mentés</button>
                                            <button onclick="cancelQuickEdit(<?= $model->id ?>)" class="btn btn-link btn-sm p-0 quick-cancel-btn text-danger" style="display: none;">Mégse</button>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="category-slug"><?= Html::encode($model->slug) ?></code>
                                    </td>
                                    <td>
                                        <?= $model->parent ? Html::encode($model->parent->name) : '<span class="text-muted">—</span>' ?>
                                    </td>
                                    <td class="status-cell">
                                        <a href="javascript:void(0)" onclick="toggleStatus(<?= $model->id ?>)" class="text-decoration-none">
                                            <span class="badge bg-<?= $model->status == Category::STATUS_ACTIVE ? 'green-lt text-green' : 'gray-lt text-gray' ?>">
                                                <?= $model->getStatusName() ?>
                                            </span>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-blue-lt text-blue"><?= $model->count ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <?= Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>', ['update', 'id' => $model->id], [
                                                'class' => 'btn btn-sm btn-outline-primary',
                                                'title' => 'Szerkesztés'
                                            ]) ?>
                                            <?= Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>', ['delete', 'id' => $model->id], [
                                                'class' => 'btn btn-sm btn-outline-danger',
                                                'title' => 'Törlés',
                                                'data' => [
                                                    'confirm' => 'Biztosan törölni szeretnéd ezt a kategóriát?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                
                                <?php if (empty($dataProvider->getModels())): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon fs-1 mb-2 d-block text-muted" width="96" height="96" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"/><path d="M9 13v-6"/><path d="M12 10h-6"/></svg>
                                        Még nincsenek kategóriák.<br>
                                        <?= Html::a('Hozz létre egyet most', ['create'], ['class' => 'btn btn-primary btn-sm mt-2']) ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php Pjax::end(); ?>
                </div>
                
                <?php if ($dataProvider->pagination->pageCount > 1): ?>
                <div class="card-footer">
                    <?= \yii\widgets\LinkPager::widget([
                        'pagination' => $dataProvider->pagination,
                        'options' => ['class' => 'pagination mb-0 justify-content-center'],
                        'linkOptions' => ['class' => 'page-link'],
                        'activePageCssClass' => 'active',
                        'disabledPageCssClass' => 'disabled',
                        'prevPageLabel' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="15,6 9,12 15,18"/></svg>',
                        'nextPageLabel' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9,6 15,12 9,18"/></svg>',
                    ]) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gyors hozzáadás</h3>
                </div>
                <div class="card-body">
                    <?= Html::beginForm(['create'], 'post', ['class' => 'quick-add-form']) ?>
                        <div class="mb-3">
                            <label for="quick-name" class="form-label">Kategória neve</label>
                            <input type="text" name="Category[name]" id="quick-name" class="form-control" placeholder="Új kategória neve" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quick-parent" class="form-label">Szülő kategória</label>
                            <select name="Category[parent_id]" id="quick-parent" class="form-select">
                                <option value="">Nincs (főkategória)</option>
                                <?php foreach ($parentCategories as $id => $name): ?>
                                    <option value="<?= $id ?>"><?= Html::encode($name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quick-description" class="form-label">Leírás</label>
                            <textarea name="Category[description]" id="quick-description" class="form-control" rows="3" placeholder="Rövid leírás..."></textarea>
                        </div>
                        
                        <input type="hidden" name="Category[status]" value="<?= Category::STATUS_ACTIVE ?>">
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                            Kategória hozzáadása
                        </button>
                    <?= Html::endForm() ?>
                </div>
            </div>
            
            <!-- Statisztikák -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Statisztikák</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="text-muted">Összes kategória</div>
                            <div class="h3 mb-0"><?= Category::find()->count() ?></div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted">Aktív kategória</div>
                            <div class="h3 mb-0 text-success"><?= Category::find()->where(['status' => Category::STATUS_ACTIVE])->count() ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
