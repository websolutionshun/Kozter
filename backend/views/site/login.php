<?php

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Bejelentkezés';
?>
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="<?= Yii::$app->homeUrl ?>" class="navbar-brand navbar-brand-autodark">
                <?= Html::img('@web/imgs/kozter_admin_login_logo.png', ['alt' => 'Közter Admin', 'style' => 'max-height: 100px;']) ?>
            </a>
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Jelentkezz be a fiókodba</h2>
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                    <div class="mb-3">
                        <?= $form->field($model, 'email')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'E-mail cím',
                            'autofocus' => true,
                            'id' => 'email-input'
                        ])->label('E-mail cím') ?>
                    </div>
                    <div class="mb-2">
                        <?= $form->field($model, 'password')->passwordInput([
                            'class' => 'form-control',
                            'placeholder' => 'Jelszó'
                        ])->label('Jelszó') ?>
                    </div>
                    <div class="mb-2">
                        <?= $form->field($model, 'rememberMe')->checkbox([
                            'template' => '<label class="form-check">{input}<span class="form-check-label">Emlékezz rám</span></label>',
                            'class' => 'form-check-input',
                            'id' => 'remember-me-checkbox'
                        ])->label(false) ?>
                    </div>
                    <div class="form-footer">
                        <?= Html::submitButton('Bejelentkezés', [
                            'class' => 'btn btn-primary w-100',
                            'name' => 'login-button'
                        ]) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="text-center text-muted mt-3">
            Elfelejtette a jelszavát? <a href="<?= \yii\helpers\Url::to(['/elfelejtett-jelszo']) ?>" tabindex="-1">Jelszó visszaállítás</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email-input');
    const rememberCheckbox = document.getElementById('remember-me-checkbox');
    const loginForm = document.getElementById('login-form');
    
    // Betöltés: emlékezett email betöltése
    const rememberedEmail = localStorage.getItem('kozter_remembered_email');
    if (rememberedEmail) {
        emailInput.value = rememberedEmail;
        rememberCheckbox.checked = true;
    }
    
    // Form submit esemény
    loginForm.addEventListener('submit', function() {
        if (rememberCheckbox.checked) {
            // Email mentése localStorage-ba
            localStorage.setItem('kozter_remembered_email', emailInput.value);
        } else {
            // Email törlése localStorage-ból
            localStorage.removeItem('kozter_remembered_email');
        }
    });
    
    // Checkbox változás esemény
    rememberCheckbox.addEventListener('change', function() {
        if (!this.checked) {
            localStorage.removeItem('kozter_remembered_email');
        }
    });
});
</script>
