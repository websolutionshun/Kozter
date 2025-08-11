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
                <h2><?= Html::encode(Yii::$app->name) ?></h2>
            </a>
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Jelentkezzen be a fiókjába</h2>
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                    <div class="mb-3">
                        <?= $form->field($model, 'email')->textInput([
                            'class' => 'form-control',
                            'placeholder' => 'E-mail cím',
                            'autofocus' => true
                        ])->label('E-mail cím') ?>
                    </div>
                    <div class="mb-2">
                        <?= $form->field($model, 'password')->passwordInput([
                            'class' => 'form-control',
                            'placeholder' => 'Jelszó'
                        ])->label('Jelszó') ?>
                    </div>
                    <div class="mb-2">
                        <label class="form-check">
                            <?= $form->field($model, 'rememberMe')->checkbox([
                                'class' => 'form-check-input',
                                'template' => '{input}'
                            ])->label(false) ?>
                            <span class="form-check-label">Emlékezzen rám</span>
                        </label>
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
            Még nincs fiókja? <a href="#" tabindex="-1">Regisztráció</a>
        </div>
    </div>
</div>
