<?php

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var \backend\models\ForgotPasswordForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Elfelejtett jelszó';
?>
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="<?= Yii::$app->homeUrl ?>" class="navbar-brand navbar-brand-autodark">
                <h2><?= Html::encode(Yii::$app->name) ?></h2>
            </a>
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Jelszó visszaállítás</h2>
                <p class="text-muted mb-4">
                    Add meg az e-mail címed, és küldünk egy linket a jelszó visszaállításához.
                </p>
                
                <?php $form = ActiveForm::begin(['id' => 'forgot-password-form']); ?>
                    <div class="mb-3">
                        <?= $form->field($model, 'email')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'E-mail cím',
                            'autofocus' => true
                        ])->label('E-mail cím') ?>
                    </div>
                    <div class="form-footer">
                        <?= Html::submitButton('Jelszó visszaállítási link küldése', [
                            'class' => 'btn btn-primary w-100',
                            'name' => 'forgot-password-button'
                        ]) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="text-center text-muted mt-3">
            Mégis bejelentkezés? <a href="<?= \yii\helpers\Url::to(['site/login']) ?>" tabindex="-1">Vissza a bejelentkezéshez</a>
        </div>
    </div>
</div> 