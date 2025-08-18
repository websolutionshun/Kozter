<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Profil';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profil-view">
    <div class="row">
        <!-- Bal oldali oszlop -->
        <div class="col-md-6">
            <!-- Személyes adatok kártya -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Személyes adatok</h3>
                    <div class="card-actions">
                        <?= Html::a('
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                                <path d="M16 5l3 3"/>
                            </svg>
                            Szerkesztés', ['szerkesztes'], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Felhasználónév</div>
                            <div class="datagrid-content">
                                <div class="d-flex align-items-center">
                                    <span class="avatar me-2" style="<?= $model->getProfileImageUrl() ? 'background-image: url('.Html::encode($model->getProfileImageUrl()).')' : 'background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTJaIiBmaWxsPSIjNzQ4OTlCIi8+CjxwYXRoIGQ9Ik0yMCAyMEMyMCAxNi42ODYzIDEzLjMxMzcgMTMgMTIgMTNDMTAuNjg2MyAxMyA0IDE2LjY4NjMgNCAyMEg0LjM0MzI1SDE5LjY1NjdIMjBaIiBmaWxsPSIjNzQ4OTlCIi8+Cjwvc3ZnPgo=)' ?>"></span>
                                    <?= Html::encode($model->username) ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($model->nickname): ?>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Becenév</div>
                            <div class="datagrid-content">
                                <span class="badge bg-blue-lt text-blue"><?= Html::encode($model->nickname) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Email cím</div>
                            <div class="datagrid-content">
                                <a href="mailto:<?= Html::encode($model->email) ?>" class="text-decoration-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"/>
                                        <path d="M3 7l9 6l9 -6"/>
                                    </svg>
                                    <?= Html::encode($model->email) ?>
                                </a>
                            </div>
                        </div>
                        <?php if ($model->bio): ?>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Bemutatkozás</div>
                            <div class="datagrid-content">
                                <div class="text-muted"><?= nl2br(Html::encode($model->bio)) ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Fiók állapot</div>
                            <div class="datagrid-content">
                                <?php if ($model->status == \common\models\User::STATUS_ACTIVE): ?>
                                    <span class="badge bg-green-lt text-green">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 12l5 5l10 -10"/>
                                        </svg>
                                        Aktív
                                    </span>
                                <?php elseif ($model->status == \common\models\User::STATUS_INACTIVE): ?>
                                    <span class="badge bg-yellow-lt text-yellow">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 9v4"/>
                                            <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"/>
                                            <path d="M12 16h.01"/>
                                        </svg>
                                        Inaktív
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-red-lt text-red">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M18 6l-12 12"/>
                                            <path d="M6 6l12 12"/>
                                        </svg>
                                        Törölve
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                            <span class="badge bg-blue-lt text-blue"><?= Html::encode($role->name) ?></span>
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
                                Neked még nincsenek szerepköröd hozzárendelve.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Jobb oldali kártya - Profilkép és statisztikák -->
        <div class="col-md-6">
            <!-- Profilkép kártya -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Profilkép</h3>
                </div>
                <div class="card-body text-center">
                    <?php if ($model->profile_image): ?>
                        <img src="<?= Html::encode($model->getProfileImageUrl()) ?>" alt="Profilkép" class="avatar avatar-xl mb-3" style="width: 128px; height: 128px;">
                        <div class="fw-medium text-success">Profilkép beállítva</div>
                    <?php else: ?>
                        <div class="avatar avatar-xl mb-3" style="width: 128px; height: 128px; background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTJaIiBmaWxsPSIjNzQ4OTlCIi8+CjxwYXRoIGQ9Ik0yMCAyMEMyMCAxNi42ODYzIDEzLjMxMzcgMTMgMTIgMTNDMTAuNjg2MyAxMyA0IDE2LjY4NjMgNCAyMEg0LjM0MzI1SDE5LjY1NjdIMjBaIiBmaWxsPSIjNzQ4OTlCIi8+Cjwvc3ZnPgo=)"></div>
                        <div class="text-muted">Alapértelmezett profilkép</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Fiók információk kártya -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Fiók információk</h3>
                </div>
                <div class="card-body">
                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Felhasználó ID</div>
                            <div class="datagrid-content">
                                <span class="badge bg-gray-lt text-gray">#<?= Html::encode($model->id) ?></span>
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Regisztráció dátuma</div>
                            <div class="datagrid-content">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"/>
                                    <path d="M16 3v4"/>
                                    <path d="M8 3v4"/>
                                    <path d="M4 11h16"/>
                                </svg>
                                <?= date('Y.m.d H:i', $model->created_at) ?>
                                <small class="text-muted ms-1">(<?= Yii::$app->formatter->asRelativeTime($model->created_at) ?>)</small>
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Utolsó frissítés</div>
                            <div class="datagrid-content">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/>
                                    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/>
                                </svg>
                                <?= date('Y.m.d H:i', $model->updated_at) ?>
                                <small class="text-muted ms-1">(<?= Yii::$app->formatter->asRelativeTime($model->updated_at) ?>)</small>
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Profil teljessége</div>
                            <div class="datagrid-content">
                                <?php 
                                $completeness = 0;
                                $total = 5; // username, email, nickname, bio, profile_image
                                
                                if ($model->username) $completeness++;
                                if ($model->email) $completeness++;
                                if ($model->nickname) $completeness++;
                                if ($model->bio) $completeness++;
                                if ($model->profile_image) $completeness++;
                                
                                $percentage = round(($completeness / $total) * 100);
                                ?>
                                <div class="progress progress-sm mb-1">
                                    <div class="progress-bar" style="width: <?= $percentage ?>%" role="progressbar" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted"><?= $percentage ?>% teljes (<?= $completeness ?>/<?= $total ?> mező kitöltve)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
