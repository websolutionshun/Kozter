<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

<h1>Jelszó visszaállítás</h1>

<p>Kedves <?= Html::encode($user->username) ?>!</p>

<p>Jelszó visszaállítási kérést kaptunk a fiókjához. Ha te voltál, kattints az alábbi gombra a jelszó visszaállításához:</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="<?= $resetLink ?>" class="btn">Jelszó visszaállítása</a>
</div>

<div class="highlight">
    <p><strong>Fontos biztonsági információk:</strong></p>
    <ul style="margin: 10px 0; padding-left: 20px;">
        <li>Ez a link 1 órán belül lejár</li>
        <li>A link csak egyszer használható</li>
        <li>Ha nem te kérted a jelszó visszaállítást, figyelmen kívül hagyhatod ezt az emailt</li>
    </ul>
</div>

<p>Ha a fenti gomb nem működik, másold be ezt a linket a böngésződbe:</p>
<p style="word-break: break-all; background-color: #f8fafc; padding: 10px; border-radius: 4px; font-family: monospace;">
    <?= Html::encode($resetLink) ?>
</p>

<p>Ha problémád van, vagy nem te kérted ezt a jelszó visszaállítást, kérjük, vedd fel velünk a kapcsolatot.</p>

<p>Üdvözlettel,<br>
<strong><?= Html::encode(Yii::$app->name) ?> csapata</strong></p>
