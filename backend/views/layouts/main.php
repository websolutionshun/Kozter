<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use common\widgets\Breadcrumb;
use yii\helpers\Html;

AppAsset::register($this);

// Function to check if current route is active
function isActiveRoute($route) {
    $currentRoute = '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
    $currentController = Yii::$app->controller->id;
    
    // Magyar URL-ek ellenőrzése - controller szintű aktiválás minden aloldalra
    if ($route === '/felhasznalok' && $currentController === 'user') return 'active';
    if ($route === '/szerepkorok' && $currentController === 'role') return 'active';
    if ($route === '/jogosultsagok' && $currentController === 'permission') return 'active';
    if ($route === '/bejegyzesek' && $currentController === 'post') return 'active';
    if ($route === '/kategoriak' && $currentController === 'category') return 'active';
    if ($route === '/cimkek' && $currentController === 'tag') return 'active';
    if ($route === '/mediatar' && $currentController === 'media') return 'active';
    if ($route === '/rendszerlogok' && $currentController === 'log') return 'active';
    if ($route === '/sitemap' && $currentController === 'sitemap') return 'active';
    if ($route === '/profil' && $currentController === 'profil') return 'active';
    if ($route === '/fooldal' && $currentRoute === '/site/index') return 'active';
    return $currentRoute === $route ? 'active' : '';
}

// Function to check if current controller is active (for controller-level menu items)
function isActiveController($controller) {
    $currentController = Yii::$app->controller->id;
    return $currentController === $controller ? 'active' : '';
}
?>
<?php $this->beginPage() ?>
<?php
// Átirányítás bejelentkezési oldalra, ha a felhasználó nincs bejelentkezve
if (Yii::$app->user->isGuest) {
    // Jelszó visszaállítási és egyéb kivételes útvonalak ellenőrzése
    $currentRoute = '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
    
    // Kivételek listája - ezekre az útvonalakra nem irányítunk át bejelentkezésre
    $allowedGuestRoutes = [
        '/site/reset-password',        // Jelszó visszaállítás
        // További bővítési lehetőségek:
        // '/site/verify-email',       // Email megerősítés
        // '/site/signup',             // Regisztráció (ha engedélyezve)
        // '/site/forgot-password',    // Elfelejtett jelszó (már kezelve AccessControl-ban)
        // '/site/terms',              // Felhasználási feltételek
        // '/site/privacy',            // Adatvédelmi szabályzat
        // '/api/webhook',             // API webhook végpontok
    ];
    
    if (!in_array($currentRoute, $allowedGuestRoutes)) {
        return Yii::$app->response->redirect(['/bejelentkezes']);
    }
}
?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
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
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
                    aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark" style="padding-bottom: 0;">
                    <?= Html::a(Html::img('/imgs/kozter_admin_logo.png', ['alt' => Html::encode(Yii::$app->name), 'class' => 'navbar-brand-image', 'style' => 'height: 5rem;']), Yii::$app->homeUrl, ['class' => 'navbar-brand-link']) ?>
                </h1>

                <?php if (!Yii::$app->user->isGuest): ?>
                    <div class="navbar-nav flex-row d-lg-none">
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                                aria-label="Open user menu">
                                <span class="avatar avatar-sm"
                                    style="<?= (Yii::$app->user->identity && Yii::$app->user->identity instanceof \common\models\User && Yii::$app->user->identity->getProfileImageUrl()) ? 'background-image: url('.Html::encode(Yii::$app->user->identity->getProfileImageUrl()).')' : 'background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTJaIiBmaWxsPSIjNzQ4OTlCIi8+CjxwYXRoIGQ9Ik0yMCAyMEMyMCAxNi42ODYzIDEzLjMxMzcgMTMgMTIgMTNDMTAuNjg2MyAxMyA0IDE2LjY4NjMgNCAyMEg0LjM0MzI1SDE5LjY1NjdIMjBaIiBmaWxsPSIjNzQ4OTlCIi8+Cjwvc3ZnPgo=)' ?>"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <?= Html::a('Profil', ['/profil'], ['class' => 'dropdown-item']) ?>
                                <div class="dropdown-divider"></div>
                                <?= Html::beginForm(['/kijelentkezes'], 'post', ['class' => 'd-inline']) ?>
                                <?= Html::submitButton('Kijelentkezés', ['class' => 'dropdown-item']) ?>
                                <?= Html::endForm() ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-2">
                        <li class="nav-item">
                            <?= Html::a('
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0"/><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/></svg>
                                </span>
                                <span class="nav-link-title">
                                    Főoldal
                                </span>', ['/fooldal'], ['class' => 'nav-link ' . isActiveRoute('/fooldal')]) ?>
                        </li>

                        <?php if (!Yii::$app->user->isGuest): ?>
                            <?php 
                            $userModulesActive = in_array(Yii::$app->controller->id, ['user', 'role', 'permission']);
                            $contentModulesActive = in_array(Yii::$app->controller->id, ['post', 'category', 'tag', 'media']);
                            ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle <?= $userModulesActive ? 'active' : '' ?>" href="#navbar-users" data-bs-toggle="dropdown"
                                    data-bs-auto-close="false" role="button" aria-expanded="true">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Felhasználók
                                    </span>
                                </a>
                                <div class="dropdown-menu show">
                                    <?= Html::a('
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/></svg>
                                    </span>
                                    Felhasználók kezelése', ['/felhasznalok'], ['class' => 'dropdown-item ' . isActiveRoute('/felhasznalok'), 'style' => isActiveRoute('/felhasznalok') ? 'color: #FFF !important;' : '']) ?>
                                    <?= Html::a('
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="3"/><path d="M12 1v6m0 6v6"/><path d="M21 12h-6m-6 0H3"/></svg>
                                    </span>
                                    Szerepkörök', ['/szerepkorok'], ['class' => 'dropdown-item ' . isActiveRoute('/szerepkorok'), 'style' => isActiveRoute('/szerepkorok') ? 'color: #FFF !important;' : '']) ?>
                                    <?= Html::a('
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/><path d="M8 11.973c0 2.51 1.79 4.527 4 4.527c2.21 0 4 -2.017 4 -4.527s-1.79 -4.527 -4 -4.527c-2.21 0 -4 2.017 -4 4.527z"/><path d="M8 12h8"/><path d="M12 9v6"/></svg>
                                    </span>
                                    Jogosultságkezelés', ['/jogosultsagok'], ['class' => 'dropdown-item ' . isActiveRoute('/jogosultsagok'), 'style' => isActiveRoute('/jogosultsagok') ? 'color: #FFF !important;' : '']) ?>
                                </div>
                            </li>

                            <li class="nav-item">
                                <?= Html::a('
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                            <path d="M9 9l1 0"/>
                                            <path d="M9 13l6 0"/>
                                            <path d="M9 17l6 0"/>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Bejegyzések kezelése
                                    </span>', ['/bejegyzesek'], ['class' => 'nav-link ' . isActiveRoute('/bejegyzesek')]) ?>
                            </li>

                            <li class="nav-item">
                                <?= Html::a('
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M9 4h6l3 7h-12l3 -7z"/>
                                            <path d="M9 4v10a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2v-10"/>
                                            <path d="M15 4v10a2 2 0 0 0 2 2h2a2 2 0 0 0 2 -2v-10"/>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Kategóriák kezelése
                                    </span>', ['/kategoriak'], ['class' => 'nav-link ' . isActiveRoute('/kategoriak')]) ?>
                            </li>

                            <li class="nav-item">
                                <?= Html::a('
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M7.859 6h8.282l.412 .412l.117 .117l.126 .16l.161 .251l.064 .139l.36 1.444l.42 1.68c.221 .884 .476 1.91 .715 2.871c.024 .097 .047 .194 .07 .29l.049 .208l.014 .074l.004 .039l-.001 .01l0 .01l-.009 .077l-.029 .176l-.032 .148l-.035 .134l-.045 .142l-.063 .155l-.073 .138l-.084 .131l-.09 .123l-.102 .118l-.105 .105l-.118 .102l-.123 .09l-.131 .084l-.138 .073l-.155 .063l-.142 .045l-.134 .035l-.148 .032l-.176 .029l-.077 .009l-.010 0l-.010 -.001l-.039 -.004l-.074 -.014l-.208 -.049c-.096 -.023 -.193 -.046 -.29 -.07c-.961 -.239 -1.987 -.494 -2.871 -.715l-1.68 -.42l-1.444 -.36l-.139 -.064l-.251 -.161l-.16 -.126l-.117 -.117l-.412 -.412z"/>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Címkék kezelése
                                    </span>', ['/cimkek'], ['class' => 'nav-link ' . isActiveRoute('/cimkek')]) ?>
                            </li>

                            <li class="nav-item">
                                <?= Html::a('
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M15 8h.01"/>
                                            <path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z"/>
                                            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5"/>
                                            <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3"/>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Médiatár
                                    </span>', ['/mediatar'], ['class' => 'nav-link ' . isActiveRoute('/mediatar')]) ?>
                            </li>

                            <li class="nav-item">
                                <?= Html::a('
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"/>
                                            <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"/>
                                            <path d="M16 5l3 3"/>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Rendszerlogok
                                    </span>', ['/rendszerlogok'], ['class' => 'nav-link ' . isActiveRoute('/rendszerlogok')]) ?>
                            </li>

                            <?php if (Yii::$app->user->identity && Yii::$app->user->identity instanceof \common\models\User && Yii::$app->user->identity->hasPermission('sitemap_view')): ?>
                            <li class="nav-item">
                                <?= Html::a('
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M3 7v4a1 1 0 0 0 1 1h3"/>
                                            <path d="M7 7v10"/>
                                            <path d="M10 8v8a1 1 0 0 0 1 1h2a1 1 0 0 0 1 -1v-8a1 1 0 0 0 -1 -1h-2a1 1 0 0 0 -1 1z"/>
                                            <path d="M17 7v4a1 1 0 0 0 1 1h3"/>
                                            <path d="M21 7v10"/>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Sitemap kezelő
                                    </span>', ['/sitemap'], ['class' => 'nav-link ' . isActiveRoute('/sitemap')]) ?>
                            </li>
                            <?php endif; ?>

                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                            <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Rendszerbeállítások
                                    </span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </aside>

        <div class="page-wrapper">
            <!-- Header -->
            <header class="navbar navbar-expand-md d-none d-lg-flex d-print-none" data-bs-theme="light">
                <div class="container-fluid">
                    <!-- BEGIN NAVBAR TOGGLER -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                        aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!-- END NAVBAR TOGGLER -->
                    <div class="navbar-nav flex-row order-md-last">
                        <div class="d-none d-md-flex">
                            <div class="nav-item">
                                <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" aria-label="Enable dark mode" title="Enable dark mode">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon">
                                        <path
                                            d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z">
                                        </path>
                                    </svg>
                                </a>
                                <a href="?theme=light" class="nav-link px-0 hide-theme-light" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" aria-label="Enable light mode" title="Enable light mode">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon">
                                        <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                        <path
                                            d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                            <div class="nav-item dropdown d-none d-md-flex">
                                <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                                    aria-label="Show notifications" data-bs-auto-close="outside">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon">
                                        <path
                                            d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6">
                                        </path>
                                        <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
                                    </svg>
                                    <span class="badge bg-red"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                                    <div class="card">
                                        <div class="card-header d-flex">
                                            <h3 class="card-title">Értesítések</h3>
                                            <div class="btn-close ms-auto" data-bs-dismiss="dropdown"></div>
                                        </div>
                                        <div class="list-group list-group-flush list-group-hoverable">
                                            <div class="list-group-item">
                                                <div class="row align-items-center">
                                                    <div class="col-auto"><span
                                                            class="status-dot status-dot-animated bg-red d-block"></span>
                                                    </div>
                                                    <div class="col text-truncate">
                                                        <a href="#" class="text-body d-block">Rendszer frissítés</a>
                                                        <div class="d-block text-secondary text-truncate mt-n1">Új
                                                            funkciók elérhetők a rendszerben</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="row align-items-center">
                                                    <div class="col-auto"><span class="status-dot d-block"></span></div>
                                                    <div class="col text-truncate">
                                                        <a href="#" class="text-body d-block">Új felhasználó</a>
                                                        <div class="d-block text-secondary text-truncate mt-n1">Új
                                                            felhasználó regisztrált a rendszerbe</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <a href="#" class="btn btn-outline w-100">Összes archiválása</a>
                                                </div>
                                                <div class="col">
                                                    <a href="#" class="btn btn-outline w-100">Mind olvasottként</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="nav-item dropdown d-none d-md-flex me-3">
                                <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                                    aria-label="Show app menu" data-bs-auto-close="outside">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/apps -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon">
                                        <path
                                            d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                                        </path>
                                        <path
                                            d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                                        </path>
                                        <path
                                            d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                                        </path>
                                        <path d="M14 7l6 0"></path>
                                        <path d="M17 4l0 6"></path>
                                    </svg>
                                </a>
                                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Gyorsindítók</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-6">
                                                    <?= Html::a('
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/></svg>
                                                        <div class="h4">Felhasználók</div>', ['/felhasznalok'], ['class' => 'text-center text-decoration-none ' . (isActiveController('user') ? 'text-primary' : 'text-muted')]) ?>
                                                </div>
                                                <div class="col-6">
                                                    <?= Html::a('
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="3"/><path d="M12 1v6m0 6v6"/><path d="M21 12h-6m-6 0H3"/></svg>
                                                        <div class="h4">Szerepkörök</div>', ['/szerepkorok'], ['class' => 'text-center text-decoration-none ' . (isActiveController('role') ? 'text-primary' : 'text-muted')]) ?>
                                                </div>
                                                <div class="col-6">
                                                    <?= Html::a('
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/><path d="M8 11.973c0 2.51 1.79 4.527 4 4.527c2.21 0 4 -2.017 4 -4.527s-1.79 -4.527 -4 -4.527c-2.21 0 -4 2.017 -4 4.527z"/><path d="M8 12h8"/><path d="M12 9v6"/></svg>
                                                        <div class="h4">Jogosultságok</div>', ['/jogosultsagok'], ['class' => 'text-center text-decoration-none ' . (isActiveController('permission') ? 'text-primary' : 'text-muted')]) ?>
                                                </div>
                                                <div class="col-6">
                                                    <?= Html::a('
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><path d="M9 9l1 0"/><path d="M9 13l6 0"/><path d="M9 17l6 0"/></svg>
                                                        <div class="h4">Bejegyzések</div>', ['/bejegyzesek'], ['class' => 'text-center text-decoration-none ' . (isActiveController('post') ? 'text-primary' : 'text-muted')]) ?>
                                                </div>
                                                <div class="col-6">
                                                    <?= Html::a('
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 8h.01"/><path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z"/><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5"/><path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3"/></svg>
                                                        <div class="h4">Médiatár</div>', ['/mediatar'], ['class' => 'text-center text-decoration-none ' . (isActiveController('media') ? 'text-primary' : 'text-muted')]) ?>
                                                </div>
                                                <div class="col-6">
                                                    <?= Html::a('
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"/><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"/><path d="M16 5l3 3"/></svg>
                                                        <div class="h4">Rendszerlogok</div>', ['/rendszerlogok'], ['class' => 'text-center text-decoration-none ' . (isActiveController('log') ? 'text-primary' : 'text-muted')]) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown"
                                    aria-label="Open user menu">
                                    <span class="avatar avatar-sm"
                                        style="<?= (Yii::$app->user->identity && Yii::$app->user->identity instanceof \common\models\User && Yii::$app->user->identity->getProfileImageUrl()) ? 'background-image: url('.Html::encode(Yii::$app->user->identity->getProfileImageUrl()).')' : 'background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTJaIiBmaWxsPSIjNzQ4OTlCIi8+CjxwYXRoIGQ9Ik0yMCAyMEMyMCAxNi42ODYzIDEzLjMxMzcgMTMgMTIgMTNDMTAuNjg2MyAxMyA0IDE2LjY4NjMgNCAyMEg0LjM0MzI1SDE5LjY1NjdIMjBaIiBmaWxsPSIjNzQ4OTlCIi8+Cjwvc3ZnPgo=)' ?>"></span>
                                    <div class="d-none d-xl-block ps-2">
                                        <div><?= Html::encode(Yii::$app->user->identity ? (Yii::$app->user->identity instanceof \common\models\User ? Yii::$app->user->identity->username : '') : '') ?></div>
                                        <div class="mt-1 small text-secondary">Admin</div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <?= Html::a('Profil', ['/profil'], ['class' => 'dropdown-item']) ?>
                                    <a href="#" class="dropdown-item">Beállítások</a>
                                    <div class="dropdown-divider"></div>
                                    <?= Html::beginForm(['/kijelentkezes'], 'post', ['class' => 'd-inline w-100']) ?>
                                    <?= Html::submitButton('Kijelentkezés', ['class' => 'dropdown-item border-0 bg-transparent']) ?>
                                    <?= Html::endForm() ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="nav-item">
                                <?= Html::a('Bejelentkezés', ['/bejelentkezes'], ['class' => 'btn btn-primary']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="collapse navbar-collapse" id="navbar-menu">
                        <!-- BEGIN NAVBAR MENU -->
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo env('FRONTEND_URL'); ?>" target="_blank">
                                    <span
                                        class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler.io/icons/icon/home -->
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg></span>
                                    <span class="nav-link-title"> Éles weboldal </span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span
                                        class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler.io/icons/icon/package -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"></path>
                                            <path d="M12 12l8 -4.5"></path>
                                            <path d="M12 12l0 9"></path>
                                            <path d="M12 12l-8 -4.5"></path>
                                            <path d="M16 5.25l-8 4.5"></path>
                                        </svg></span>
                                    <span class="nav-link-title"> Fejlesztői központ </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="./accordion.html">
                                                Accordion
                                                <span
                                                    class="badge badge-sm bg-green-lt text-uppercase ms-auto">New</span>
                                            </a>
                                            <a class="dropdown-item" href="./alerts.html"> Alerts </a>
                                        </div>
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="./markdown.html"> Markdown </a>
                                            <a class="dropdown-item" href="./navigation.html"> Navigation </a>
                                            <a class="dropdown-item" href="./offcanvas.html"> Offcanvas </a>
                                            <a class="dropdown-item" href="./pagination.html"> Pagination </a>
                                            <a class="dropdown-item" href="./placeholder.html"> Placeholder </a>
                                        </div>
                                    </div>
                                </div>
                            </li>



                        </ul>
                        <!-- END NAVBAR MENU -->
                    </div>
                </div>
            </header>
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-fluid">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <!-- Breadcrumb -->
                            <?php if (isset($this->params['breadcrumbs']) && !empty($this->params['breadcrumbs'])): ?>
                                <?= Breadcrumb::widget([
                                    'items' => $this->params['breadcrumbs'],
                                    'options' => ['class' => 'mb-1'],
                                    'listOptions' => ['class' => 'breadcrumb mb-0']
                                ]) ?>
                            <?php endif; ?>
                            
                            <h2 class="page-title">
                                <?= Html::encode($this->title) ?>
                            </h2>
                        </div>
                        <?php if (Yii::$app->controller->id === 'media'): ?>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                <?php if (Yii::$app->controller->action->id === 'index'): ?>
                                    <?= Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg> Új média feltöltése', ['media/create'], ['class' => 'btn btn-primary']) ?>
                                <?php elseif (Yii::$app->controller->action->id === 'view'): ?>
                                    <?= Html::a('Szerkesztés', ['media/update', 'id' => Yii::$app->request->get('id')], ['class' => 'btn btn-primary']) ?>
                                    <?= Html::a('Törlés', ['media/delete', 'id' => Yii::$app->request->get('id')], [
                                        'class' => 'btn btn-outline-danger',
                                        'data' => [
                                            'confirm' => 'Biztosan törölni szeretnéd ezt a médiát?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-fluid">
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