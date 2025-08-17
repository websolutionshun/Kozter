<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Post;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $categories common\models\Category[] */
/* @var $authors common\models\User[] */
/* @var $statusFilter string */
/* @var $categoryFilter string */
/* @var $searchQuery string */

$this->title = 'Bejegyzések kezelése';
$this->params['breadcrumbs'][] = 'Bejegyzések';

// Globális függvények definiálása a bejegyzések kezeléséhez
$this->registerJs("
// Quick edit
window.quickEdit = function(id) {
    var row = $('#post-' + id);
    var title = row.find('.post-title').text();
    var slug = row.find('.post-slug').text();
    
    row.find('.post-title').html('<input type=\"text\" class=\"form-control form-control-sm\" id=\"quick-title-' + id + '\" value=\"' + title + '\">');
    row.find('.post-slug').html('<input type=\"text\" class=\"form-control form-control-sm\" id=\"quick-slug-' + id + '\" value=\"' + slug + '\">');
    row.find('.quick-edit-btn').hide();
    row.find('.quick-save-btn, .quick-cancel-btn').show();
};

window.cancelQuickEdit = function(id) {
    location.reload(); // Egyszerű megoldás
};

window.saveQuickEdit = function(id) {
    var title = $('#quick-title-' + id).val();
    var slug = $('#quick-slug-' + id).val();
    
    $.ajax({
        url: '" . Url::to(['post/quick-edit', 'id' => '']) . "' + id,
        type: 'POST',
        data: {
            title: title,
            slug: slug,
            '_csrf': '" . Yii::$app->request->csrfToken . "'
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
            alert('Hiba a mentés során: ' + error);
        }
    });
};

// Toggle status
window.toggleStatus = function(id) {
    $.ajax({
        url: '" . Url::to(['post/toggle-status', 'id' => '']) . "' + id,
        type: 'POST',
        data: {
            '_csrf': '" . Yii::$app->request->csrfToken . "'
        },
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                var statusCell = $('#post-' + id).find('.status-cell');
                statusCell.html('<span class=\"badge bg-' + (data.status == 1 ? 'green-lt text-green' : 'gray-lt text-gray') + '\">' + data.statusName + '</span>');
            } else {
                alert('Hiba: ' + (data.message || 'Ismeretlen hiba történt'));
            }
        },
        error: function(xhr, status, error) {
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
        alert('Válassz ki legalább egy bejegyzést.');
        return false;
    }
    
    if (action === 'delete') {
        if (!confirm('Biztosan törölni szeretnéd a kiválasztott bejegyzéseket?')) {
            return false;
        }
        
        var form = $('<form method=\"post\" action=\"' + '" . Url::to(['post/bulk-delete']) . "' + '\"></form>');
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

// Auto-generate slug from title
$('#post-title').on('keyup', function() {
    var title = $(this).val();
    var slug = title.toLowerCase()
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
    $('#post-slug').val(slug);
});
", yii\web\View::POS_READY);
?>

<div class="post-index">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        Bejegyzések
                        <span class="badge bg-blue-lt text-blue ms-2"><?= $dataProvider->totalCount ?></span>
                    </h3>
                    <div>
                        <?= Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg> Új bejegyzés', ['create'], ['class' => 'btn btn-primary btn-sm']) ?>
                    </div>
                </div>
                
                <!-- Szűrők -->
                <div class="card-body border-bottom">
                    <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2']) ?>
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Keresés..." value="<?= Html::encode($searchQuery) ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Minden állapot</option>
                                <?php foreach (Post::getStatusOptions() as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= $statusFilter == $value ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-select">
                                <option value="">Minden kategória</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category->id ?>" <?= $categoryFilter == $category->id ? 'selected' : '' ?>><?= Html::encode($category->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Szűrés</button>
                            <?= Html::a('Törlés', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
                        </div>
                    <?= Html::endForm() ?>
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

                    <?php Pjax::begin(['id' => 'post-pjax']); ?>
                    
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">
                                        <input type="checkbox" id="select-all" class="form-check-input">
                                    </th>
                                    <th>Cím</th>
                                    <th>Szerző</th>
                                    <th>Kategóriák</th>
                                    <th>Címkék</th>
                                    <th style="width: 100px;">Állapot</th>
                                    <th style="width: 120px;">Dátum</th>
                                    <th style="width: 120px;" class="text-center">Műveletek</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataProvider->getModels() as $model): ?>
                                <tr id="post-<?= $model->id ?>">
                                    <td>
                                        <input type="checkbox" name="selection[]" value="<?= $model->id ?>" class="form-check-input select-row">
                                    </td>
                                    <td>
                                        <div class="post-title fw-semibold"><?= Html::encode($model->title) ?></div>
                                        <div class="text-muted small">
                                            <code class="post-slug"><?= Html::encode($model->slug) ?></code> | 
                                            ID: <?= $model->id ?> | 
                                            <a href="javascript:void(0)" onclick="quickEdit(<?= $model->id ?>)" class="quick-edit-btn text-decoration-none">Gyors szerkesztés</a>
                                            <button onclick="saveQuickEdit(<?= $model->id ?>)" class="btn btn-link btn-sm p-0 quick-save-btn text-success" style="display: none;">Mentés</button>
                                            <button onclick="cancelQuickEdit(<?= $model->id ?>)" class="btn btn-link btn-sm p-0 quick-cancel-btn text-danger" style="display: none;">Mégse</button>
                                        </div>
                                        <div class="text-muted small mt-1">
                                            <?= $model->getShortContent(100) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-sm me-2" style="background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTJaIiBmaWxsPSIjNzQ4OTlCIi8+CjxwYXRoIGQ9Ik0yMCAyMEMyMCAxNi42ODYzIDEzLjMxMzcgMTMgMTIgMTNDMTAuNjg2MyAxMyA0IDE2LjY4NjMgNCAyMEg0LjM0MzI1SDE5LjY1NjdIMjBaIiBmaWxsPSIjNzQ4OTlCIi8+Cjwvc3ZnPgo=)"></span>
                                            <div>
                                                <div class="fw-semibold"><?= Html::encode($model->author->username ?? 'N/A') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($model->categories)): ?>
                                            <?php foreach ($model->categories as $category): ?>
                                                <span class="badge bg-blue-lt text-blue me-1"><?= Html::encode($category->name) ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($model->tags)): ?>
                                            <?php foreach ($model->tags as $tag): ?>
                                                <span class="badge bg-gray-lt text-gray me-1"><?= Html::encode($tag->name) ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="status-cell">
                                        <a href="javascript:void(0)" onclick="toggleStatus(<?= $model->id ?>)" class="text-decoration-none">
                                            <span class="badge bg-<?= $model->status == Post::STATUS_PUBLISHED ? 'green-lt text-green' : ($model->status == Post::STATUS_DRAFT ? 'gray-lt text-gray' : 'red-lt text-red') ?>">
                                                <?= $model->getStatusName() ?>
                                            </span>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="text-muted small">
                                            <?= $model->published_at ? date('Y-m-d H:i', $model->published_at) : date('Y-m-d H:i', $model->created_at) ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <?= Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/></svg>', ['view', 'id' => $model->id], [
                                                'class' => 'btn btn-sm btn-outline-info',
                                                'title' => 'Megtekintés'
                                            ]) ?>
                                            <?= Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>', ['update', 'id' => $model->id], [
                                                'class' => 'btn btn-sm btn-outline-primary',
                                                'title' => 'Szerkesztés'
                                            ]) ?>
                                            <?= Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>', ['delete', 'id' => $model->id], [
                                                'class' => 'btn btn-sm btn-outline-danger',
                                                'title' => 'Törlés',
                                                'data' => [
                                                    'confirm' => 'Biztosan törölni szeretnéd ezt a bejegyzést?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                
                                <?php if (empty($dataProvider->getModels())): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon fs-1 mb-2 d-block text-muted" width="96" height="96" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><path d="M9 9l1 0"/><path d="M9 13l6 0"/><path d="M9 17l6 0"/></svg>
                                        Még nincsenek bejegyzések.<br>
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
    </div>
</div>
