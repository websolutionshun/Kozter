<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Kedves <?= $user->username ?>!

Jelszó visszaállítási kérést kaptunk a fiókjához.

Kattints az alábbi linkre a jelszó visszaállításához:
<?= $resetLink ?>

FONTOS BIZTONSÁGI INFORMÁCIÓK:
- Ez a link 1 órán belül lejár
- A link csak egyszer használható  
- Ha nem te kérted a jelszó visszaállítást, figyelmen kívül hagyhatod ezt az emailt

Ha problémád van, vagy nem te kérted ezt a jelszó visszaállítást, kérjük, vedd fel velünk a kapcsolatot.

Üdvözlettel,
<?= Yii::$app->name ?> csapata
