<?php

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var \frontend\models\ResetPasswordForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\Alert;

$this->title = 'Jelszó visszaállítás';
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
                <h2 class="h2 text-center mb-4">Új jelszó beállítása</h2>
                <?= Alert::widget() ?>
                <p class="text-muted mb-4">
                    Kérjük, adja meg az új jelszavát. A jelszónak legalább 8 karakter hosszúnak kell lennie.
                </p>
                
                <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                    <div class="mb-3">
                        <?= $form->field($model, 'password')->passwordInput([
                            'class' => 'form-control',
                            'placeholder' => 'Új jelszó',
                            'autofocus' => true
                        ])->label('Új jelszó') ?>
                    </div>
                    <div class="form-footer">
                        <?= Html::submitButton('Jelszó beállítása', [
                            'class' => 'btn btn-primary w-100',
                            'name' => 'reset-password-button'
                        ]) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="text-center text-muted mt-3">
            Visszatérés a <a href="<?= \yii\helpers\Url::to(['/bejelentkezes']) ?>">bejelentkezéshez</a>
        </div>
    </div>
</div>
