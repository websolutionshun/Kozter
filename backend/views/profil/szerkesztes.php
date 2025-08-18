<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Profil szerkesztése';
$this->params['breadcrumbs'][] = ['label' => 'Profil', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Szerkesztés';
?>

<div class="profil-update">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>
    
    <div class="row">
        <!-- Bal oldali kártya - Form mezők -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Profil adatok</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <?= $form->field($model, 'username')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Felhasználónév'
                        ]) ?>
                    </div>
                    
                    <div class="mb-3">
                        <?= $form->field($model, 'email')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'email@example.com'
                        ]) ?>
                    </div>
                    
                    <div class="mb-3">
                        <?= $form->field($model, 'nickname')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Becenév'
                        ]) ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Új jelszó <small class="text-muted">(opcionális)</small></label>
                        <input type="password" class="form-control" name="new_password" placeholder="Új jelszó">
                        <small class="form-hint">Csak akkor töltsd ki, ha meg szeretnéd változtatni a jelszót.</small>
                    </div>
                    
                    <div class="mb-3">
                        <?= $form->field($model, 'bio')->textarea([
                            'class' => 'form-control',
                            'rows' => 4,
                            'placeholder' => 'Írj pár sort magadról...'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Jobb oldali kártya - Profilkép -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Profilkép</h3>
                    <div class="card-actions">
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
                <div class="card-body text-center">
                    <?php if ($model->profile_image): ?>
                        <div class="mb-4">
                            <img src="<?= Html::encode($model->getProfileImageUrl()) ?>" alt="Profilkép" class="avatar avatar-xl mb-3" style="width: 128px; height: 128px;">
                            <div class="fw-medium mb-2">Jelenlegi profilkép</div>
                            <?= Html::a('
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 7l16 0"/>
                                    <path d="M10 11l0 6"/>
                                    <path d="M14 11l0 6"/>
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                                </svg>
                                Profilkép törlése', ['profilkep-torles'], [
                                'class' => 'btn btn-outline-danger',
                                'data' => [
                                    'confirm' => 'Biztosan törölni szeretnéd a profilképet?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    <?php else: ?>
                        <div class="mb-4">
                            <div class="avatar avatar-xl mb-3" style="width: 128px; height: 128px; background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTJaIiBmaWxsPSIjNzQ4OTlCIi8+CjxwYXRoIGQ9Ik0yMCAyMEMyMCAxNi42ODYzIDEzLjMxMzcgMTMgMTIgMTNDMTAuNjg2MyAxMyA0IDE2LjY4NjMgNCAyMEg0LjM0MzI1SDE5LjY1NjdIMjBaIiBmaWxsPSIjNzQ4OTlCIi8+Cjwvc3ZnPgo=)"></div>
                            <div class="text-muted">Nincs profilkép beállítva</div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Új profilkép feltöltése</label>
                        <input type="file" class="form-control" name="profile_image" accept="image/*">
                        <small class="form-hint">Válassz ki egy új profilképet (JPG, PNG, GIF formátum).</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Műveleti gombok -->
    <div class="row mt-3">
        <div class="col-12 text-end">
            <?= Html::submitButton('
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M9 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                    <path d="M16 5l3 3"/>
                </svg>
                Frissítés', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Mégse', ['index'], ['class' => 'btn btn-secondary ms-2']) ?>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>
