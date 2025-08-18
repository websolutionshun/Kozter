<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Role */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Szerepkörök', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="role-view">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Szerepkör részletei</h3>
                    <div class="card-actions">
                        <?= Html::a('
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                <path d="M16 5l3 3"/>
                            </svg>
                            Szerkesztés', ['update', 'id' => $model->id], ['class' => 'btn btn-primary me-2']) ?>
                        <?= Html::a('
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5 12l14 0"/>
                                <path d="M5 12l6 6"/>
                                <path d="M5 12l6 -6"/>
                            </svg>
                            Vissza', ['index'], ['class' => 'btn btn-secondary']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Szerepkör neve</label>
                                <div class="form-control-plaintext">
                                    <strong><?= Html::encode($model->name) ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Létrehozva</label>
                                <div class="form-control-plaintext">
                                    <?= date('Y.m.d H:i', $model->created_at) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Leírás</label>
                                <div class="form-control-plaintext">
                                    <?= $model->description ? Html::encode($model->description) : '<span class="text-muted">Nincs leírás megadva</span>' ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($model->updated_at): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Utoljára módosítva</label>
                                <div class="form-control-plaintext">
                                    <?= date('Y.m.d H:i', $model->updated_at) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jogosultságok</h3>
                    <div class="card-subtitle">
                        Összesen <?= count($model->permissions) ?> jogosultság van hozzárendelve ehhez a szerepkörhöz
                    </div>
                </div>
                <div class="card-body">
                    <?php if (count($model->permissions) > 0): ?>
                        <div class="row g-2">
                            <?php foreach ($model->permissions as $permission): ?>
                                <div class="col-auto">
                                    <span class="badge bg-blue text-blue-fg fs-6 p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 12l5 5l10 -10"/>
                                        </svg>
                                        <?= Html::encode($permission->name) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if (count($model->permissions) > 10): ?>
                        <div class="mt-3">
                            <small class="text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M12 8h.01"/>
                                    <path d="M11 12h1v4h1"/>
                                </svg>
                                Ez a szerepkör sok jogosultsággal rendelkezik. Gondosan ellenőrizze a biztonsági beállításokat.
                            </small>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="empty">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M9 12l2 2l4 -4"/>
                                </svg>
                            </div>
                            <p class="empty-title">Nincs jogosultság hozzárendelve</p>
                            <p class="empty-subtitle text-muted">
                                Ehhez a szerepkörhöz még nem tartozik egyetlen jogosultság sem.
                            </p>
                            <div class="empty-action">
                                <?= Html::a('Jogosultságok hozzáadása', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Felhasználók</h3>
                    <div class="card-subtitle">
                        Összesen <?= count($model->users) ?> felhasználó rendelkezik ezzel a szerepkörrel
                    </div>
                </div>
                <div class="card-body">
                    <?php if (count($model->users) > 0): ?>
                        <div class="row">
                            <?php foreach ($model->users as $user): ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <span class="avatar me-3" style="background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTJaIiBmaWxsPSIjNzQ4OTlCIi8+CjxwYXRoIGQ9Ik0yMCAyMEMyMCAxNi42ODYzIDEzLjMxMzcgMTMgMTIgMTNDMTAuNjg2MyAxMyA0IDE2LjY4NjMgNCAyMEg0LjM0MzI1SDE5LjY1NjdIMjBaIiBmaWxsPSIjNzQ4OTlCIi8+Cjwvc3ZnPgo=)"></span>
                                                <div>
                                                    <div class="font-weight-medium"><?= Html::encode($user->username) ?></div>
                                                    <div class="text-muted"><?= Html::encode($user->email) ?></div>
                                                </div>
                                            </div>
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
                                    <path d="M12 8v4"/>
                                    <path d="M12 16h.01"/>
                                </svg>
                            </div>
                            <p class="empty-title">Nincs felhasználó</p>
                            <p class="empty-subtitle text-muted">
                                Egyetlen felhasználó sem rendelkezik ezzel a szerepkörrel.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 