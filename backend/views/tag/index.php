<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Tag;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Címkék kezelése';
$this->params['breadcrumbs'][] = 'Címkék';

// Globális függvények definiálása a címkék kezeléséhez
$this->registerJs("
// Quick edit
window.quickEdit = function(id) {
    var row = $('#tag-' + id);
    var name = row.find('.tag-name').text();
    var slug = row.find('.tag-slug').text();
    var color = row.find('.tag-color').data('color');
    
    row.find('.tag-name').html('<input type=\"text\" class=\"form-control form-control-sm\" id=\"quick-name-' + id + '\" value=\"' + name + '\">');
    row.find('.tag-slug').html('<input type=\"text\" class=\"form-control form-control-sm\" id=\"quick-slug-' + id + '\" value=\"' + slug + '\">');
    row.find('.tag-color').html('<input type=\"color\" class=\"form-control form-control-color form-control-sm\" id=\"quick-color-' + id + '\" value=\"' + color + '\">');
    row.find('.quick-edit-btn').hide();
    row.find('.quick-save-btn, .quick-cancel-btn').show();
};

window.cancelQuickEdit = function(id) {
    location.reload(); // Egyszerű megoldás
};

window.saveQuickEdit = function(id) {
    var name = $('#quick-name-' + id).val();
    var slug = $('#quick-slug-' + id).val();
    var color = $('#quick-color-' + id).val();
    
    $.ajax({
        url: '" . Url::to(['tag/quick-edit', 'id' => '']) . "' + id,
        type: 'POST',
        data: {
            name: name,
            slug: slug,
            color: color,
            '_csrf': '" . Yii::$app->request->csrfToken . "'
        },
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                location.reload();
            } else {
                alert('Hiba: ' + (data.message || 'Ismeretlen hiba történt'));
                console.error('Validation errors:', data.errors);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', xhr.responseText);
            alert('Hiba a mentés során: ' + error);
        }
    });
};

// Toggle status
window.toggleStatus = function(id) {
    $.ajax({
        url: '" . Url::to(['tag/toggle-status', 'id' => '']) . "' + id,
        type: 'POST',
        data: {
            '_csrf': '" . Yii::$app->request->csrfToken . "'
        },
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                var statusCell = $('#tag-' + id).find('.status-cell');
                statusCell.html('<span class=\"badge bg-' + (data.status == 1 ? 'green-lt text-green' : 'gray-lt text-gray') + '\">' + data.statusName + '</span>');
            } else {
                alert('Hiba: ' + (data.message || 'Ismeretlen hiba történt'));
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', xhr.responseText);
            alert('Hiba az állapot módosítása során: ' + error);
        }
    });
};
", yii\web\View::POS_HEAD);

// Event handlers a DOM ready után
$this->registerJs("
// Bulk actions
$('#bulk-action-form').on('submit', function(e) {
    e.preventDefault();
    var action = $('#bulk-action-selector').val();
    var selected = $('input.select-row:checked');
    
    if (selected.length === 0) {
        alert('Válassz ki legalább egy címkét.');
        return false;
    }
    
    if (action === 'delete') {
        if (!confirm('Biztosan törölni szeretnéd a kiválasztott címkéket?')) {
            return false;
        }
        
        var form = $('<form method=\"post\" action=\"' + '" . Url::to(['tag/bulk-delete']) . "' + '\"></form>');
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

// Auto-generate slug from name in quick add
$('#quick-name').on('keyup', function() {
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
    $('#quick-slug').val(slug);
});

// Random color generator
$('#random-color-btn').on('click', function() {
    var colors = " . Json::encode(Tag::getDefaultColors()) . ";
    var randomColor = colors[Math.floor(Math.random() * colors.length)];
    $('#quick-color').val(randomColor);
});
", yii\web\View::POS_READY);
?>

<div class="tag-index">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Címkék</h3>
                    <div>
                        <?= Html::a('Új címke', ['create'], ['class' => 'btn btn-primary btn-sm']) ?>
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

                    <?php Pjax::begin(['id' => 'tag-pjax']); ?>
                    
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">
                                        <input type="checkbox" id="select-all" class="form-check-input">
                                    </th>
                                    <th>Név</th>
                                    <th>Slug</th>
                                    <th style="width: 100px;">Szín</th>
                                    <th style="width: 100px;">Állapot</th>
                                    <th style="width: 80px;" class="text-center">Elemek</th>
                                    <th style="width: 120px;" class="text-center">Műveletek</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataProvider->getModels() as $model): ?>
                                <tr id="tag-<?= $model->id ?>">
                                    <td>
                                        <input type="checkbox" name="selection[]" value="<?= $model->id ?>" class="form-check-input select-row">
                                    </td>
                                    <td>
                                        <div class="tag-name fw-semibold"><?= Html::encode($model->name) ?></div>
                                        <div class="text-muted small">
                                            ID: <?= $model->id ?> | 
                                            <a href="javascript:void(0)" onclick="quickEdit(<?= $model->id ?>)" class="quick-edit-btn text-decoration-none">Gyors szerkesztés</a>
                                            <button onclick="saveQuickEdit(<?= $model->id ?>)" class="btn btn-link btn-sm p-0 quick-save-btn text-success" style="display: none;">Mentés</button>
                                            <button onclick="cancelQuickEdit(<?= $model->id ?>)" class="btn btn-link btn-sm p-0 quick-cancel-btn text-danger" style="display: none;">Mégse</button>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="tag-slug"><?= Html::encode($model->slug) ?></code>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center tag-color" data-color="<?= Html::encode($model->color) ?>">
                                            <div class="color-preview me-2" style="width: 20px; height: 20px; border-radius: 3px; background-color: <?= Html::encode($model->color) ?>; border: 1px solid #dee2e6;"></div>
                                            <small class="text-muted"><?= Html::encode($model->color) ?></small>
                                        </div>
                                    </td>
                                    <td class="status-cell">
                                        <a href="javascript:void(0)" onclick="toggleStatus(<?= $model->id ?>)" class="text-decoration-none">
                                            <span class="badge bg-<?= $model->status == Tag::STATUS_ACTIVE ? 'green-lt text-green' : 'gray-lt text-gray' ?>">
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
                                                    'confirm' => 'Biztosan törölni szeretnéd ezt a címkét?',
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
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon fs-1 mb-2 d-block text-muted" width="96" height="96" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7.859 6h8.282l.412 .412l.117 .117l.126 .16l.161 .251l.064 .139l.36 1.444l.42 1.68c.221 .884 .476 1.91 .715 2.871c.024 .097 .047 .194 .07 .29l.049 .208l.014 .074l.004 .039l-.001 .01l0 .01l-.009 .077l-.029 .176l-.032 .148l-.035 .134l-.045 .142l-.063 .155l-.073 .138l-.084 .131l-.09 .123l-.102 .118l-.105 .105l-.118 .102l-.123 .09l-.131 .084l-.138 .073l-.155 .063l-.142 .045l-.134 .035l-.148 .032l-.176 .029l-.077 .009l-.01 0l-.01 -.001l-.039 -.004l-.074 -.014l-.208 -.049c-.096 -.023 -.193 -.046 -.29 -.07c-.961 -.239 -1.987 -.494 -2.871 -.715l-1.68 -.42l-1.444 -.36l-.139 -.064l-.251 -.161l-.16 -.126l-.117 -.117l-.412 -.412z"/></svg>
                                        Még nincsenek címkék.<br>
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
                            <label for="quick-name" class="form-label">Címke neve</label>
                            <input type="text" name="Tag[name]" id="quick-name" class="form-control" placeholder="Új címke neve" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quick-slug" class="form-label">URL név (slug)</label>
                            <input type="text" name="Tag[slug]" id="quick-slug" class="form-control" placeholder="url-nev">
                            <div class="form-text">Automatikusan generálódik a névből</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quick-color" class="form-label">Szín</label>
                            <div class="input-group">
                                <input type="color" name="Tag[color]" id="quick-color" class="form-control form-control-color" value="#007acc">
                                <button type="button" id="random-color-btn" class="btn btn-outline-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z"/><path d="M14 4h6v6h-6z"/><path d="M4 14h6v6h-6z"/><path d="M17 17l-3 3l3 3l3 -3z"/></svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quick-description" class="form-label">Leírás</label>
                            <textarea name="Tag[description]" id="quick-description" class="form-control" rows="3" placeholder="Rövid leírás..."></textarea>
                        </div>
                        
                        <input type="hidden" name="Tag[status]" value="<?= Tag::STATUS_ACTIVE ?>">
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                            Címke hozzáadása
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
                            <div class="text-muted">Összes címke</div>
                            <div class="h3 mb-0"><?= Tag::find()->count() ?></div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted">Aktív címke</div>
                            <div class="h3 mb-0 text-success"><?= Tag::find()->where(['status' => Tag::STATUS_ACTIVE])->count() ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>