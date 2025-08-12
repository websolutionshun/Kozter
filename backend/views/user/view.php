<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Felhasználókezelés', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Felhasználó részletei</h3>
                    <div class="card-actions">
                        <?= Html::a('
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                <path d="M16 5l3 3"/>
                            </svg>
                            Szerkesztés', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <polyline points="9,6 15,12 9,18"/>
                            </svg>
                            Vissza', ['index'], ['class' => 'btn btn-secondary ms-2']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">ID</div>
                            <div class="datagrid-content"><?= Html::encode($model->id) ?></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Felhasználónév</div>
                            <div class="datagrid-content">
                                <div class="d-flex align-items-center">
                                    <span class="avatar me-2" style="background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTJaIiBmaWxsPSIjNzQ4OTlCIi8+CjxwYXRoIGQ9Ik0yMCAyMEMyMCAxNi42ODYzIDEzLjMxMzcgMTMgMTIgMTNDMTAuNjg2MyAxMyA0IDE2LjY4NjMgNCAyMEg0LjM0MzI1SDE5LjY1NjdIMjBaIiBmaWxsPSIjNzQ4OTlCIi8+Cjwvc3ZnPgo=)"></span>
                                    <?= Html::encode($model->username) ?>
                                </div>
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Email cím</div>
                            <div class="datagrid-content">
                                <a href="mailto:<?= Html::encode($model->email) ?>"><?= Html::encode($model->email) ?></a>
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Állapot</div>
                            <div class="datagrid-content">
                                <?php if ($model->status == \common\models\User::STATUS_ACTIVE): ?>
                                    <span class="badge bg-success">Aktív</span>
                                <?php elseif ($model->status == \common\models\User::STATUS_INACTIVE): ?>
                                    <span class="badge bg-warning">Inaktív</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Törölve</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Létrehozva</div>
                            <div class="datagrid-content"><?= date('Y.m.d H:i:s', $model->created_at) ?></div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Utolsó módosítás</div>
                            <div class="datagrid-content"><?= date('Y.m.d H:i:s', $model->updated_at) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Szerepkörök kártya -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Szerepkörök</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($model->roles)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($model->roles as $role): ?>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="badge bg-blue"><?= Html::encode($role->name) ?></span>
                                        </div>
                                        <div class="col text-truncate">
                                            <div class="text-reset d-block"><?= Html::encode($role->name) ?></div>
                                            <?php if ($role->description): ?>
                                                <div class="d-block text-muted text-truncate mt-n1">
                                                    <?= Html::encode($role->description) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="12" r="9"/>
                                    <line x1="9" y1="9" x2="15" y2="15"/>
                                    <line x1="15" y1="9" x2="9" y2="15"/>
                                </svg>
                            </div>
                            <p class="empty-title">Nincs szerepkör</p>
                            <p class="empty-subtitle text-muted">
                                Ennek a felhasználónak még nincsenek szerepkörei hozzárendelve.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Műveletek kártya -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Műveletek</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <rect x="3" y="11" width="18" height="10" rx="2"/>
                                    <circle cx="12" cy="16" r="1"/>
                                    <path d="M7 11v-4a5 5 0 0 1 10 0v4"/>
                                </svg>
                                Jelszó visszaállítása
                            </button>
                        </div>
                        <?php if ($model->id != Yii::$app->user->id): ?>
                            <div class="list-group-item">
                                <?= Html::a('
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-red" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="4" y1="7" x2="20" y2="7"/>
                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                    </svg>
                                    Felhasználó törlése', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-danger btn-sm',
                                    'data' => [
                                        'confirm' => 'Biztosan törlöd ezt a felhasználót?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jelszó visszaállítás modal -->
<div class="modal modal-blur fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Jelszó visszaállítása</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= Html::beginForm(['reset-password', 'id' => $model->id], 'post', ['id' => 'resetPasswordForm']) ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Új jelszó</label>
                        <input type="password" class="form-control" name="password" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jelszó megerősítése</label>
                        <input type="password" class="form-control" name="password_confirm" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                    <button type="submit" class="btn btn-warning">Jelszó megváltoztatása</button>
                </div>
            <?= Html::endForm() ?>
        </div>
    </div>
</div> 