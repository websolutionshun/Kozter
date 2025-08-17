<?php

use common\models\Log;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $levels */
/** @var array $categories */

$this->title = 'Rendszerlogok';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="log-index">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"/>
                            <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"/>
                            <path d="M16 5l3 3"/>
                        </svg>
                        Szűrők
                    </h3>
                </div>
                <div class="card-body">
                    <?= Html::beginForm(['log/index'], 'get', ['class' => 'row g-3']) ?>
                        <div class="col-md-2">
                            <?= Html::label('Szint', 'level', ['class' => 'form-label']) ?>
                            <?= Html::dropDownList('level', Yii::$app->request->get('level'), 
                                ['' => 'Összes'] + $levels, 
                                ['class' => 'form-select', 'id' => 'level']) ?>
                        </div>
                        
                        <div class="col-md-2">
                            <?= Html::label('Kategória', 'category', ['class' => 'form-label']) ?>
                            <?= Html::dropDownList('category', Yii::$app->request->get('category'), 
                                ['' => 'Összes'] + array_combine($categories, $categories), 
                                ['class' => 'form-select', 'id' => 'category']) ?>
                        </div>
                        
                        <div class="col-md-2">
                            <?= Html::label('Időszak', 'date_filter', ['class' => 'form-label']) ?>
                            <?= Html::dropDownList('date_filter', Yii::$app->request->get('date_filter'), [
                                '' => 'Összes',
                                'today' => 'Ma',
                                'week' => 'Utolsó 7 nap',
                                'month' => 'Utolsó 30 nap',
                            ], ['class' => 'form-select', 'id' => 'date_filter']) ?>
                        </div>
                        
                        <div class="col-md-4">
                            <?= Html::label('Keresés', 'search', ['class' => 'form-label']) ?>
                            <?= Html::textInput('search', Yii::$app->request->get('search'), [
                                'class' => 'form-control', 
                                'placeholder' => 'Keresés az üzenetekben...',
                                'id' => 'search'
                            ]) ?>
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <?= Html::submitButton('Szűrés', ['class' => 'btn btn-primary me-2']) ?>
                            <?= Html::a('Törlés', ['log/index'], ['class' => 'btn btn-outline-secondary']) ?>
                        </div>
                    <?= Html::endForm() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 12h4l3 8l4 -16l3 8h4"/>
                        </svg>
                        Műveletek
                    </h3>
                    <div class="btn-list">
                        <?= Html::a('Statisztikák', ['log/stats'], ['class' => 'btn btn-info']) ?>
                        <?= Html::button('Régi logok törlése', [
                            'class' => 'btn btn-warning',
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#clearOldModal'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php Pjax::begin(); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'layout' => '{summary}<div class="table-responsive">{items}</div>{pager}',
                        'tableOptions' => ['class' => 'table table-vcenter'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\CheckboxColumn',
                                'options' => ['style' => 'width: 50px;'],
                            ],
                            [
                                'attribute' => 'level',
                                'label' => 'Szint',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::tag('span', $model->getLevelLabel(), [
                                        'class' => 'badge ' . $model->getLevelBadgeClass()
                                    ]);
                                },
                                'options' => ['style' => 'width: 100px;'],
                            ],
                            [
                                'attribute' => 'category',
                                'label' => 'Kategória',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if (empty($model->category)) {
                                        return '<span class="text-muted">-</span>';
                                    }
                                    return Html::tag('span', Html::encode($model->category), [
                                        'class' => 'badge bg-secondary-lt'
                                    ]);
                                },
                                'options' => ['style' => 'width: 120px;'],
                            ],
                            [
                                'attribute' => 'message',
                                'label' => 'Üzenet',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $shortMessage = $model->getShortMessage(150);
                                    return Html::encode($shortMessage);
                                },
                            ],
                            [
                                'attribute' => 'user_id',
                                'label' => 'Felhasználó',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->user) {
                                        return Html::tag('span', Html::encode($model->user->username), [
                                            'class' => 'badge bg-azure-lt'
                                        ]);
                                    }
                                    return '<span class="text-muted">Rendszer</span>';
                                },
                                'options' => ['style' => 'width: 120px;'],
                            ],
                            [
                                'attribute' => 'ip_address',
                                'label' => 'IP cím',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if (empty($model->ip_address)) {
                                        return '<span class="text-muted">-</span>';
                                    }
                                    return Html::tag('code', Html::encode($model->ip_address), [
                                        'class' => 'small'
                                    ]);
                                },
                                'options' => ['style' => 'width: 120px;'],
                            ],
                            [
                                'attribute' => 'created_at',
                                'label' => 'Időpont',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return '<div class="small">' . 
                                           '<div>' . $model->getFormattedCreatedAt() . '</div>' .
                                           '<div class="text-muted">' . $model->getRelativeTime() . '</div>' .
                                           '</div>';
                                },
                                'options' => ['style' => 'width: 150px;'],
                            ],
                            [
                                'class' => ActionColumn::class,
                                'template' => '{view} {delete}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/></svg>', 
                                            $url, [
                                                'title' => 'Megtekintés',
                                                'class' => 'btn btn-sm btn-outline-primary'
                                            ]);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>', 
                                            $url, [
                                                'title' => 'Törlés',
                                                'class' => 'btn btn-sm btn-outline-danger',
                                                'data' => [
                                                    'confirm' => 'Biztosan törölni szeretnéd ezt a log bejegyzést?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                    },
                                ],
                                'options' => ['style' => 'width: 100px;'],
                                'urlCreator' => function ($action, Log $model, $key, $index, $column) {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                            ],
                        ],
                    ]); ?>

                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tömeges törlés form -->
    <?= Html::beginForm(['log/bulk-delete'], 'post', ['id' => 'bulk-delete-form', 'style' => 'display: none;']) ?>
    <?= Html::endForm() ?>

    <!-- Régi logok törlése modal -->
    <div class="modal modal-blur fade" id="clearOldModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <?= Html::beginForm(['log/clear-old'], 'post') ?>
                <div class="modal-header">
                    <h5 class="modal-title">Régi logok törlése</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Hány napnál régebbi logokat töröljem?</label>
                        <?= Html::dropDownList('days', 30, [
                            '7' => '7 nap',
                            '14' => '14 nap',
                            '30' => '30 nap',
                            '60' => '60 nap',
                            '90' => '90 nap',
                        ], ['class' => 'form-select']) ?>
                    </div>
                    <div class="alert alert-warning">
                        <strong>Figyelem!</strong> Ez a művelet nem visszavonható.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Mégse</button>
                    <button type="submit" class="btn btn-warning">Törlés</button>
                </div>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tömeges törlés kezelése
    const bulkActions = document.createElement('div');
    bulkActions.className = 'mb-3';
    bulkActions.style.display = 'none';
    bulkActions.innerHTML = `
        <div class="alert alert-info">
            <div class="d-flex">
                <div class="flex-fill">
                    <span id="selected-count">0</span> elem kiválasztva
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="bulk-delete-btn">
                        Kiválasztottak törlése
                    </button>
                </div>
            </div>
        </div>
    `;
    
    const gridContainer = document.querySelector('.grid-view');
    if (gridContainer) {
        gridContainer.insertBefore(bulkActions, gridContainer.firstChild);
    }
    
    // Checkbox változás figyelése
    document.addEventListener('change', function(e) {
        if (e.target.matches('input[name="selection[]"]') || e.target.matches('input[name="selection_all"]')) {
            const checked = document.querySelectorAll('input[name="selection[]"]:checked').length;
            document.getElementById('selected-count').textContent = checked;
            bulkActions.style.display = checked > 0 ? 'block' : 'none';
        }
    });
    
    // Tömeges törlés gomb
    document.getElementById('bulk-delete-btn').addEventListener('click', function() {
        if (confirm('Biztosan törölni szeretnéd a kiválasztott log bejegyzéseket?')) {
            const form = document.getElementById('bulk-delete-form');
            const checkboxes = document.querySelectorAll('input[name="selection[]"]:checked');
            
            checkboxes.forEach(function(checkbox) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'selection[]';
                hiddenInput.value = checkbox.value;
                form.appendChild(hiddenInput);
            });
            
            form.submit();
        }
    });
});
</script>
