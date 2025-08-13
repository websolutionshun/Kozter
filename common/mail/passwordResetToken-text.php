<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
$appName = Html::encode(Yii::$app->name);
$username = Html::encode($user->username);
?>
=========================================
JELSZÓ VISSZAÁLLÍTÁS
=========================================

Kedves <?= $username ?>!

Jelszó visszaállítási kérést kaptunk a fiókjához. Ha te voltál, használd az alábbi linket egy új jelszó beállításához:

🔗 JELSZÓ VISSZAÁLLÍTÁSA:
<?= $resetLink ?>


🛡️ BIZTONSÁGI INFORMÁCIÓK:
=========================================
⏰ Ez a link 1 órán belül lejár
🔒 A link csak egyszer használható  
❌ Ha nem te kérted, figyelmen kívül hagyhatod ezt az emailt


Ha problémád van, vagy nem te kérted ezt a jelszó visszaállítást, kérjük, vedd fel velünk a kapcsolatot.

Üdvözlettel,
<?= $appName ?> csapata

=========================================
Ez egy automatikus email. Kérjük, ne válaszolj erre az üzenetre.
© <?= date('Y') ?> <?= $appName ?>. Minden jog fenntartva.