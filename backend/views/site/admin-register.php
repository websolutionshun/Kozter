<?php

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var \backend\models\AdminRegistrationForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\Alert;

$this->title = 'Admin Regisztráció';
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
                <h2 class="h2 text-center mb-4">Admin Felhasználó Létrehozása</h2>
                <?= Alert::widget() ?>
                <p class="text-center text-muted mb-4">Hozz létre egy új admin felhasználót a rendszerhez</p>
                
                <?php $form = ActiveForm::begin(['id' => 'admin-register-form']); ?>
                    
                    <div class="mb-3">
                        <?= $form->field($model, 'username')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'Felhasználónév',
                            'autofocus' => true
                        ])->label('Felhasználónév')->hint('Csak betűk, számok, pontok, kötőjelek és aláhúzások használhatók.') ?>
                    </div>
                    
                    <div class="mb-3">
                        <?= $form->field($model, 'email')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'E-mail cím'
                        ])->label('E-mail cím') ?>
                    </div>
                    
                    <div class="mb-3">
                        <?= $form->field($model, 'password')->passwordInput([
                            'class' => 'form-control',
                            'placeholder' => 'Jelszó'
                        ])->label('Jelszó') ?>
                    </div>
                    
                    <div class="mb-3">
                        <?= $form->field($model, 'password_repeat')->passwordInput([
                            'class' => 'form-control',
                            'placeholder' => 'Jelszó megerősítése'
                        ])->label('Jelszó megerősítése') ?>
                    </div>
                    
                    <div class="mb-3">
                        <?= $form->field($model, 'admin_key')->passwordInput([
                            'class' => 'form-control',
                            'placeholder' => 'Admin kulcs'
                        ])->label('Admin kulcs')->hint('Az .env fájlban meghatározott ADMIN_ADD_USER_MANUAL értéke.') ?>
                    </div>
                    
                    <div class="form-footer">
                        <?= Html::submitButton('Felhasználó létrehozása', [
                            'class' => 'btn btn-primary w-100',
                            'name' => 'register-button'
                        ]) ?>
                    </div>
                    
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="text-center text-muted mt-3">
            Már van fiókja? <a href="<?= \yii\helpers\Url::to(['login']) ?>" tabindex="-1">Bejelentkezés</a>
        </div>
    </div>
</div> 