<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Json;

/** @var yii\web\View $this */
/** @var common\models\Log $model */

$this->title = 'Log részletei #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rendszerlogok', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="log-view">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"/>
                            <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"/>
                            <path d="M16 5l3 3"/>
                        </svg>
                        Log részletei
                    </h3>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-vcenter'],
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'level',
                                'label' => 'Szint',
                                'format' => 'raw',
                                'value' => Html::tag('span', $model->getLevelLabel(), [
                                    'class' => 'badge ' . $model->getLevelBadgeClass()
                                ]),
                            ],
                            [
                                'attribute' => 'category',
                                'label' => 'Kategória',
                                'format' => 'raw',
                                'value' => $model->category ? Html::tag('span', Html::encode($model->category), [
                                    'class' => 'badge bg-secondary-lt'
                                ]) : '<span class="text-muted">-</span>',
                            ],
                            [
                                'attribute' => 'message',
                                'label' => 'Üzenet',
                                'format' => 'raw',
                                'value' => '<div class="border rounded p-3 bg-light">' . 
                                          nl2br(Html::encode($model->message)) . 
                                          '</div>',
                            ],
                            [
                                'attribute' => 'user_id',
                                'label' => 'Felhasználó',
                                'format' => 'raw',
                                'value' => $model->user ? 
                                    Html::a(Html::encode($model->user->username), ['/user/view', 'id' => $model->user->id], [
                                        'class' => 'badge bg-azure-lt text-decoration-none'
                                    ]) : 
                                    '<span class="text-muted">Rendszer</span>',
                            ],
                            [
                                'attribute' => 'ip_address',
                                'label' => 'IP cím',
                                'format' => 'raw',
                                'value' => $model->ip_address ? 
                                    Html::tag('code', Html::encode($model->ip_address)) : 
                                    '<span class="text-muted">-</span>',
                            ],
                            [
                                'attribute' => 'method',
                                'label' => 'HTTP metódus',
                                'format' => 'raw',
                                'value' => $model->method ? 
                                    Html::tag('span', Html::encode($model->method), [
                                        'class' => 'badge bg-cyan-lt'
                                    ]) : 
                                    '<span class="text-muted">-</span>',
                            ],
                            [
                                'attribute' => 'url',
                                'label' => 'URL',
                                'format' => 'raw',
                                'value' => $model->url ? 
                                    '<div class="border rounded p-2 bg-light small" style="word-break: break-all;">' . 
                                    Html::encode($model->url) . 
                                    '</div>' : 
                                    '<span class="text-muted">-</span>',
                            ],
                            [
                                'attribute' => 'created_at',
                                'label' => 'Létrehozva',
                                'format' => 'raw',
                                'value' => '<div>' . 
                                          '<div><strong>' . $model->getFormattedCreatedAt() . '</strong></div>' .
                                          '<div class="text-muted small">' . $model->getRelativeTime() . '</div>' .
                                          '</div>',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Műveletek kártya -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/>
                            <path d="M8 11.973c0 2.51 1.79 4.527 4 4.527c2.21 0 4 -2.017 4 -4.527s-1.79 -4.527 -4 -4.527c-2.21 0 -4 2.017 -4 4.527z"/>
                            <path d="M8 12h8"/>
                            <path d="M12 9v6"/>
                        </svg>
                        Műveletek
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?= Html::a('Vissza a listához', ['index'], [
                            'class' => 'btn btn-primary'
                        ]) ?>
                        
                        <?= Html::a('Törlés', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-outline-danger',
                            'data' => [
                                'confirm' => 'Biztosan törölni szeretnéd ezt a log bejegyzést?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
            
            <!-- User Agent információ -->
            <?php if (!empty($model->user_agent)): ?>
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 12h4l3 8l4 -16l3 8h4"/>
                        </svg>
                        Böngésző információ
                    </h3>
                </div>
                <div class="card-body">
                    <div class="small text-muted" style="word-break: break-word;">
                        <?= Html::encode($model->user_agent) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Kiegészítő adatok -->
            <?php if (!empty($model->data)): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 7l5 5l5 -5l5 5"/>
                            <path d="M3 17l5 5l5 -5l5 5"/>
                        </svg>
                        Kiegészítő adatok
                    </h3>
                </div>
                <div class="card-body">
                    <?php 
                    $decodedData = $model->getDecodedData();
                    if (is_array($decodedData) || is_object($decodedData)): 
                    ?>
                        <pre class="border rounded p-3 bg-light small" style="max-height: 300px; overflow-y: auto;"><?= Html::encode(Json::encode($decodedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
                    <?php else: ?>
                        <div class="border rounded p-3 bg-light small"><?= Html::encode($model->data) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
