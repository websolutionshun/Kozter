<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $roles common\models\Role[] */
/* @var $selectedRoles array */

$this->title = 'Új felhasználó';
$this->params['breadcrumbs'][] = ['label' => 'Felhasználókezelés', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-create">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Új felhasználó létrehozása</h3>
                    <div class="card-actions">
                        <?= Html::a('
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <polyline points="9,6 15,12 9,18"/>
                            </svg>
                            Vissza', ['index'], ['class' => 'btn btn-secondary']) ?>
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
                        <?= $form->field($model, 'password')->passwordInput([
                            'class' => 'form-control',
                            'placeholder' => 'Jelszó'
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Jelszó megerősítése</label>
                            <input type="password" class="form-control" name="password_confirm" placeholder="Jelszó megerősítése">
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
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Létrehozás', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Mégse', ['index'], ['class' => 'btn btn-secondary ms-2']) ?>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<script>
// Jelszó megerősítés validáció
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.querySelector('input[name="User[password]"]');
    const confirmInput = document.querySelector('input[name="password_confirm"]');
    
    function validatePassword() {
        if (passwordInput.value !== confirmInput.value) {
            confirmInput.setCustomValidity('A jelszavak nem egyeznek');
        } else {
            confirmInput.setCustomValidity('');
        }
    }
    
    if (passwordInput && confirmInput) {
        passwordInput.addEventListener('input', validatePassword);
        confirmInput.addEventListener('input', validatePassword);
    }
});
</script> 