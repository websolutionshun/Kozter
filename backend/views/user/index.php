<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Felhasználókezelés';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Felhasználók listája</h3>
                    <div class="card-actions">
                        <?= Html::a('
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Új felhasználó', ['create'], ['class' => 'btn btn-primary btn-sm']) ?>
                    </div>
                </div>
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-muted">
                            Mutass:
                            <div class="mx-2 d-inline-block">
                                <select class="form-select form-select-sm" id="pageSize" onchange="changePageSize()">
                                    <option value="10">10</option>
                                    <option value="20" selected>20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            bejegyzést
                        </div>
                        <div class="ms-auto text-muted">
                            Keresés:
                            <div class="ms-2 d-inline-block">
                                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Keresés...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Felhasználónév</th>
                                <th>Email</th>
                                <th>Szerepkörök</th>
                                <th>Állapot</th>
                                <th>Létrehozva</th>
                                <th class="w-1">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataProvider->getModels() as $model): ?>
                            <tr>
                                <td><?= Html::encode($model->id) ?></td>
                                <td>
                                    <div class="d-flex py-1 align-items-center">
                                        <span class="avatar me-2" style="background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTJaIiBmaWxsPSIjNzQ4OTlCIi8+CjxwYXRoIGQ9Ik0yMCAyMEMyMCAxNi42ODYzIDEzLjMxMzcgMTMgMTIgMTNDMTAuNjg2MyAxMyA0IDE2LjY4NjMgNCAyMEg0LjM0MzI1SDE5LjY1NjdIMjBaIiBmaWxsPSIjNzQ4OTlCIi8+Cjwvc3ZnPgo=)"></span>
                                        <div class="flex-fill">
                                            <div class="fw-medium"><?= Html::encode($model->username) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= Html::encode($model->email) ?></td>
                                <td>
                                    <?php if (!empty($model->roles)): ?>
                                        <?php foreach ($model->roles as $role): ?>
                                            <span class="badge bg-blue-lt text-blue me-1 mb-1"><?= Html::encode($role->name) ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Nincs szerepkör</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($model->status == \common\models\User::STATUS_ACTIVE): ?>
                                        <span class="badge bg-green-lt text-green">Aktív</span>
                                    <?php elseif ($model->status == \common\models\User::STATUS_INACTIVE): ?>
                                        <span class="badge bg-yellow-lt text-yellow">Inaktív</span>
                                    <?php else: ?>
                                        <span class="badge bg-red-lt text-red">Törölve</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('Y.m.d H:i', $model->created_at) ?></td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <?= Html::a('
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                            </svg>', ['view', 'id' => $model->id], ['class' => 'btn btn-white btn-sm', 'title' => 'Megtekintés']) ?>
                                        <?= Html::a('
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                                <path d="M16 5l3 3"/>
                                            </svg>', ['update', 'id' => $model->id], ['class' => 'btn btn-white btn-sm', 'title' => 'Szerkesztés']) ?>
                                        <?php if ($model->id != Yii::$app->user->id): ?>
                                            <?= Html::a('
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-red" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <line x1="4" y1="7" x2="20" y2="7"/>
                                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                                </svg>', ['delete', 'id' => $model->id], [
                                                'class' => 'btn btn-white btn-sm',
                                                'title' => 'Törlés',
                                                'data' => [
                                                    'confirm' => 'Biztosan törlöd ezt a felhasználót?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex align-items-center">
                    <?php
                    echo yii\widgets\LinkPager::widget([
                        'pagination' => $dataProvider->pagination,
                        'options' => ['class' => 'pagination m-0 ms-auto'],
                        'linkOptions' => ['class' => 'page-link'],
                        'activePageCssClass' => 'active',
                        'disabledPageCssClass' => 'disabled',
                        'prevPageLabel' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="15,6 9,12 15,18"/></svg>',
                        'nextPageLabel' => '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9,6 15,12 9,18"/></svg>',
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changePageSize() {
    const pageSize = document.getElementById('pageSize').value;
    const url = new URL(window.location);
    url.searchParams.set('per-page', pageSize);
    window.location.href = url.toString();
}

document.getElementById('searchInput').addEventListener('input', function() {
    // Egyszerű kliens oldali keresés implementálható itt
});
</script> 