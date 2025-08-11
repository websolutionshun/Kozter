<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception*/

use yii\helpers\Html;

$this->title = $name;
?>
<div class="page page-center">
    <div class="container-tight py-4">
        <div class="empty">
            <div class="empty-header"><?= Html::encode($this->title) ?></div>
            <p class="empty-title">Oops… Valami hiba történt</p>
            <div class="empty-subtitle text-muted">
                <?= nl2br(Html::encode($message)) ?>
            </div>
            <p class="empty-subtitle text-muted">
                A fenti hiba történt, miközben a webszerver feldolgozta a kérését.
            </p>
            <p class="empty-subtitle text-muted">
                Kérjük, vegye fel velünk a kapcsolatot, ha úgy gondolja, hogy ez szerveroldali hiba. Köszönjük.
            </p>
            <div class="empty-action">
                <a href="<?= Yii::$app->homeUrl ?>" class="btn btn-primary">
                    <!-- Download SVG icon from http://tabler-icons.io/i/arrow-left -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0"/><path d="M5 12l6 6"/><path d="M5 12l6 -6"/></svg>
                    Vissza a főoldalra
                </a>
            </div>
        </div>
    </div>
</div>
