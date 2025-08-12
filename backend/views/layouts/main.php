<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="icon" type="image/png" href="/imgs/icons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/imgs/icons/favicon.svg" />
    <link rel="shortcut icon" href="/imgs/icons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/imgs/icons/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Köztér" />
    <link rel="manifest" href="/imgs/icons/site.webmanifest" />
</head>

<body>
<?php $this->beginBody() ?>
    <div class="page">
        <!-- Sidebar -->
        <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark">
                    <?= Html::a(Html::encode(Yii::$app->name), Yii::$app->homeUrl, ['class' => 'navbar-brand-link']) ?>
                </h1>
                
                <?php if (!Yii::$app->user->isGuest): ?>
                <div class="navbar-nav flex-row d-lg-none">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                            <span class="avatar avatar-sm" style="background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTJaIiBmaWxsPSIjNzQ4OTlCIi8+CjxwYXRoIGQ9Ik0yMCAyMEMyMCAxNi42ODYzIDEzLjMxMzcgMTMgMTIgMTNDMTAuNjg2MyAxMyA0IDE2LjY4NjMgNCAyMEg0LjM0MzI1SDE5LjY1NjdIMjBaIiBmaWxsPSIjNzQ4OTlCIi8+Cjwvc3ZnPgo=)"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="#" class="dropdown-item">Profil</a>
                            <div class="dropdown-divider"></div>
                            <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline']) ?>
                                <?= Html::submitButton('Kijelentkezés', ['class' => 'dropdown-item']) ?>
                            <?= Html::endForm() ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-3">
                        <li class="nav-item">
                            <?= Html::a('
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0"/><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/></svg>
                                </span>
                                <span class="nav-link-title">
                                    Főoldal
                                </span>', ['/site/index'], ['class' => 'nav-link']) ?>
                        </li>
                        
                        <?php if (!Yii::$app->user->isGuest): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"/><path d="M12 12l8 -4.5"/><path d="M12 12l0 9"/><path d="M12 12l-8 -4.5"/></svg>
                                </span>
                                <span class="nav-link-title">
                                    Adminisztráció
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/></svg>
                                    </span>
                                    Felhasználók
                                </a>
                                <a class="dropdown-item" href="#">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"/><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"/></svg>
                                    </span>
                                    Beállítások
                                </a>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </aside>
        
        <!-- Header -->
        <header class="navbar navbar-expand-md d-print-none d-lg-flex d-none" data-bs-theme="light">
            <div class="container-xl">
                <div class="row flex-fill align-items-center">
                    <div class="col">
                        <div class="navbar-nav flex-row order-md-last">
                            <div class="d-none d-md-flex">
                                <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z"/></svg>
                                </a>
                                <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="4"/><path d="m3 12l1.5 0m15 0l1.5 0m-9 -9l0 1.5m0 15l0 1.5m-6.4 -15.4l1.1 1.1m12.8 12.8l1.1 1.1m-15.4 -6.4l1.1 -1.1m12.8 -12.8l1.1 -1.1"/></svg>
                                </a>
                            </div>
                            <?php if (!Yii::$app->user->isGuest): ?>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                                        <span class="avatar avatar-sm" style="background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTJaIiBmaWxsPSIjNzQ4OTlCIi8+CjxwYXRoIGQ9Ik0yMCAyMEMyMCAxNi42ODYzIDEzLjMxMzcgMTMgMTIgMTNDMTAuNjg2MyAxMyA0IDE2LjY4NjMgNCAyMEg0LjM0MzI1SDE5LjY1NjdIMjBaIiBmaWxsPSIjNzQ4OTlCIi8+Cjwvc3ZnPgo=)"></span>
                                        <div class="d-none d-xl-block ps-2">
                                            <div><?= Html::encode(Yii::$app->user->identity->username) ?></div>
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <a href="#" class="dropdown-item">Profil</a>
                                        <div class="dropdown-divider"></div>
                                        <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline']) ?>
                                            <?= Html::submitButton('Kijelentkezés', ['class' => 'dropdown-item']) ?>
                                        <?= Html::endForm() ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="nav-item">
                                    <?= Html::a('Bejelentkezés', ['/site/login'], ['class' => 'btn btn-primary']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                                <?= Html::encode($this->title) ?>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
