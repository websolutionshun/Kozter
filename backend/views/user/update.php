<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $roles common\models\Role[] */
/* @var $selectedRoles array */

$this->title = 'Szerkesztés: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Felhasználókezelés', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Szerkesztés';
?>

<div class="user-update">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Felhasználó szerkesztése</h3>
                    <div class="card-actions">
                        <?= Html::a('
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <polyline points="9,6 15,12 9,18"/>
                            </svg>
                            Vissza', ['view', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
                    </div>
                </div>
                <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'card-body'],
                ]); ?>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <?= $form->field($model, 'username')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Felhasználónév'
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'email')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'email@example.com'
                        ]) ?>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Állapot</label>
                            <select name="User[status]" class="form-select">
                                <option value="<?= \common\models\User::STATUS_ACTIVE ?>" <?= $model->status == \common\models\User::STATUS_ACTIVE ? 'selected' : '' ?>>Aktív</option>
                                <option value="<?= \common\models\User::STATUS_INACTIVE ?>" <?= $model->status == \common\models\User::STATUS_INACTIVE ? 'selected' : '' ?>>Inaktív</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Új jelszó <small class="text-muted">(opcionális)</small></label>
                            <input type="password" class="form-control" name="new_password" placeholder="Új jelszó">
                            <small class="form-hint">Csak akkor töltsd ki, ha meg szeretnéd változtatni a jelszót.</small>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Szerepkör</label>
                    <div class="form-selectgroup form-selectgroup-boxes">
                        <?php foreach ($roles as $role): ?>
                            <label class="form-selectgroup-item">
                                <input type="radio" name="role" value="<?= $role->id ?>" class="form-selectgroup-input" 
                                    <?= in_array($role->id, $selectedRoles) ? 'checked' : '' ?>>
                                <span class="form-selectgroup-label">
                                    <div>
                                        <div class="fw-medium"><?= Html::encode($role->name) ?></div>
                                        <?php if ($role->description): ?>
                                            <div class="text-muted"><?= Html::encode($role->description) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <small class="form-hint">Válaszd ki a felhasználó szerepkörét.</small>
                </div>
                
                <div class="card-footer text-end">
                    <?= Html::submitButton('
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                            <path d="M16 5l3 3"/>
                        </svg>
                        Frissítés', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Mégse', ['view', 'id' => $model->id], ['class' => 'btn btn-secondary ms-2']) ?>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div> 