<?php

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Bejelentkezés';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Kérjük, töltse ki az alábbi mezőket a bejelentkezéshez:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'email')->textInput([
                    'autofocus' => true,
                    'id' => 'email-input'
                ]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox([
                    'id' => 'remember-me-checkbox'
                ]) ?>

                <div class="my-1 mx-0" style="color:#999;">
                    Elfelejtette a jelszavát? <?= Html::a('Jelszó visszaállítás', ['/jelszo-visszaallitas']) ?>.
                    <br>
                    Új megerősítő e-mailt szeretne? <?= Html::a('Újraküldés', ['/email-ujrakuldese']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Bejelentkezés', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
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
