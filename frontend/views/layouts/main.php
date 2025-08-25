<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="icon" type="image/png" href="/imgs/icons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/imgs/icons/favicon.svg" />
    <link rel="shortcut icon" href="/imgs/icons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/imgs/icons/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Köztér" />
    <link rel="manifest" href="/imgs/icons/site.webmanifest" />
    
    <!-- Google Fonts - megadott betűtípusok -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link property="stylesheet" rel='stylesheet' id='googlefonts-css' href='https://fonts.googleapis.com/css?family=Bungee:400|Encode+Sans+Expanded:100,200,300,400,500,600,700,800,900&subset=latin' type='text/css' media='all' />
    <!-- Font Awesome ikonok globálisan -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkfQttEvP0S3QYx0Q7ESy90D1u2qE3XWUEK7iQ8b6+7bQ0Pp90NQO0F3A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Kozter színvilág CSS -->
    <style>
        :root {
            --kozter-yellow: #FFD700;
            --kozter-yellow-light: #FFF89A;
            --kozter-yellow-dark: #E6C200;
            --kozter-blue: #1E3A8A;
            --kozter-blue-light: #3B82F6;
            --kozter-blue-dark: #1E40AF;
            --kozter-green: #74C9BE;
            --kozter-green-light: #10B981;
            --kozter-gray: #6B7280;
            --kozter-gray-light: #F3F4F6;
            --kozter-gray-dark: #374151;
            --kozter-light-blue: #74C9BE;
        }
        
        body {
            font-family: 'Encode Sans Expanded', Arial, sans-serif;
            background-color: #FFC82E;
            color: var(--kozter-gray-dark);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Bungee', Arial, sans-serif;
            font-weight: 700;
            color: var(--kozter-blue-dark);
        }

        li {
            font-family: 'Bungee', Arial, sans-serif;
        }
        
        .navbar-kozter {
            background: linear-gradient(135deg, var(--kozter-yellow) 0%, var(--kozter-yellow-light) 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-bottom: 3px solid var(--kozter-blue);
        }
        
        .navbar-kozter .navbar-brand {
            font-family: 'Encode Sans Expanded', Arial, sans-serif;
            font-weight: 700;
            color: var(--kozter-blue-dark) !important;
            font-size: 1.5rem;
        }
        
        .navbar-kozter .nav-link {
            color: var(--kozter-blue-dark) !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .navbar-kozter .nav-link:hover {
            color: var(--kozter-blue) !important;
        }
        
        .featured-card {
            background: linear-gradient(135deg, var(--kozter-blue) 0%, var(--kozter-blue-dark) 100%);
            color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(30, 58, 138, 0.3);
        }
        
        .category-section {
            background: white;
            border-radius: 8px;
            border-left: 4px solid var(--kozter-yellow);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .category-title {
            color: var(--kozter-blue-dark);
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--kozter-yellow-light);
        }
        
        .post-card {
            background: white;
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #E5E7EB;
        }
        
        .post-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .post-title {
            color: var(--kozter-blue-dark);
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.4;
        }
        
        .post-title:hover {
            color: var(--kozter-blue);
            text-decoration: none;
        }
        
        .post-excerpt {
            color: var(--kozter-gray);
            font-size: 0.9rem;
            line-height: 1.5;
        }
        
        .post-meta {
            color: var(--kozter-gray);
            font-size: 0.8rem;
        }
        
        .btn-kozter {
            background: linear-gradient(135deg, var(--kozter-yellow) 0%, var(--kozter-yellow-dark) 100%);
            border: none;
            color: var(--kozter-blue-dark);
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .btn-kozter:hover {
            background: linear-gradient(135deg, var(--kozter-yellow-dark) 0%, var(--kozter-yellow) 100%);
            color: var(--kozter-blue);
            transform: translateY(-1px);
        }
        
        .footer-kozter {
            background-color: #0f172a;
            color: var(--kozter-green);
        }
        .footer-kozter .footer-title {
            color: var(--kozter-green);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            font-family: 'Bungee', Arial, sans-serif;
            font-size: .95rem;
        }
        .footer-kozter .footer-nav a {
            color: var(--kozter-green);
            text-decoration: none;
            font-weight: 800;
            text-transform: uppercase;
            margin: .35rem 0;
            font-family: 'Bungee', Arial, sans-serif;
            letter-spacing: .5px;
        }
        .footer-kozter .footer-nav a:hover { color: var(--kozter-yellow); }
        .footer-kozter .footer-social a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background-color: var(--kozter-yellow);
            color: #111827;
            margin-right: .5rem;
            font-size: 1.1rem;
            transition: all .2s ease;
        }
        .footer-kozter .footer-social a:hover { background-color: var(--kozter-yellow-dark); color: #0f172a; transform: translateY(-1px); }
        .footer-kozter .footer-contact { color: var(--kozter-green); text-decoration: none; font-weight: 700; }
        .footer-kozter .footer-contact:hover { color: var(--kozter-yellow); }
        .footer-kozter .footer-bottom { border-top: 1px solid #1f2937; color: #9ca3af; }
        .footer-kozter .footer-bottom a { color: var(--kozter-green); text-decoration: none; }
        .footer-kozter .footer-bottom a:hover { color: var(--kozter-yellow); }
        
        /* Responsive fejlesztések */
        @media (max-width: 768px) {
            .featured-card {
                text-align: center;
            }
            
            .category-section {
                margin-bottom: 2rem;
            }
            
            .post-card {
                margin-bottom: 1rem;
            }
        }
        
        /* === ÚJSÁGSZERŰ KÉTSOROS FEJLÉC (MAGAZIN-STÍLUS) === */
        .site-header .header-top {
            background-color: #0f172a;
            color: var(--kozter-green);
            font-size: 0.9rem;
        }
        .site-header .header-top a { color: var(--kozter-green); text-decoration: none; }
        .site-header .mini-menu a { color: var(--kozter-green); margin: 0 .5rem; font-weight: 600; font-size: 0.9rem; }
        /* Felső mini-menü teljes középre igazítása */
        .site-header .header-top .container-fluid { position: relative; }
        .site-header .mini-menu { position: absolute; left: 50%; transform: translateX(-50%); }
        .site-header .mini-menu a:hover { color: var(--kozter-yellow); }
        .site-header .header-second {
            background-color: #111827;
            color: var(--kozter-green);
            border-bottom: 1px solid #1f2937;
        }
        .site-header .logo-center a {
            font-family: 'Encode Sans Expanded', Arial, sans-serif;
            font-weight: 800;
            font-size: 2.2rem;
            letter-spacing: 0.5px;
            color: var(--kozter-green);
            text-decoration: none;
        }
        .site-header .header-icon { color: var(--kozter-green); }
        .site-header .header-icon:hover { color: var(--kozter-yellow); }
        .site-header .support-btn { background-color: var(--kozter-yellow); color: #111827; border: 0; font-weight: 700; font-size: 14px; }
        .site-header .support-btn:hover { background-color: var(--kozter-yellow-dark); color: #0f172a; }
        /* Keresősáv a header alatt */
        .header-search-wrap { background-color: #0b1220; border-bottom: 1px solid #1f2937; }
        .header-search-wrap input[type="text"] { border-radius: 999px; padding: .6rem 1rem; border: 1px solid #374151; background: #111827; color: #e5e7eb; }
        .header-search-wrap input[type="text"]::placeholder { color: #9ca3af; }
        .header-search-wrap .btn-search { border-radius: 999px; }
        /* Offcanvas címke-menü */
        .offcanvas-tags .offcanvas-header { background: #0f172a; color: var(--kozter-green); }
        .offcanvas-tags .offcanvas-body a { display: inline-block; margin: 0 .5rem .5rem 0; background: #111827; color: var(--kozter-green); border: 1px solid #1f2937; padding: .35rem .7rem; border-radius: 999px; text-decoration: none; font-size: .9rem; }
        .offcanvas-tags .offcanvas-body a:hover { background: #1f2937; color: var(--kozter-yellow); }

        /* Dropdown menük styling */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        .dropdown-item:hover {
            background-color: var(--kozter-yellow-light);
            color: var(--kozter-blue-dark);
        }
        
        /* Post content styling */
        .post-content {
            font-size: 1.1rem;
            line-height: 1.7;
        }
        
        .post-content h2, .post-content h3, .post-content h4 {
            margin-bottom: 1rem;
        }
        
        .post-content p {
            margin-bottom: 1.2rem;
        }
        
        .post-content blockquote {
            border-left: 4px solid var(--kozter-yellow);
            padding-left: 1rem;
            margin: 1.5rem 0;
            font-style: italic;
            background-color: var(--kozter-gray-light);
            padding: 1rem;
            border-radius: 4px;
        }
        
        /* MAGAZIN-STÍLUSÚ 3 OSZLOPOS LAYOUT */
        .homepage-kozter {
            font-size: 14px;
            line-height: 1.4;
        }
        
        .kozter-layout {
            gap: 20px;
        }
        
        /* BAL OSZLOP - Fő hírek */
        .main-column {
            border-right: 1px solid #E5E7EB;
            padding-right: 20px;
        }
        
        .featured-main {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .featured-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--kozter-blue-dark);
            line-height: 1.3;
            margin-bottom: 0.5rem;
        }
        
        .featured-title a {
            color: inherit;
            text-decoration: none;
        }
        
        .featured-title a:hover {
            color: var(--kozter-blue);
        }
        
        .featured-excerpt {
            color: var(--kozter-gray);
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }
        
        .category-badge {
            background: var(--kozter-yellow);
            color: var(--kozter-blue-dark);
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 1rem;
            display: inline-block;
        }
        
        .secondary-featured {
            background: white;
            border-radius: 6px;
            padding: 1rem;
            border: 1px solid #F3F4F6;
        }
        
        .secondary-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--kozter-blue-dark);
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }
        
        .secondary-title a {
            color: inherit;
            text-decoration: none;
        }
        
        .secondary-title a:hover {
            color: var(--kozter-blue);
        }
        
        .secondary-excerpt {
            color: var(--kozter-gray);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .more-news {
            margin-top: 2rem;
        }
        
        .section-title {
            color: var(--kozter-blue-dark);
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--kozter-yellow);
        }
        
        .news-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #F3F4F6;
        }
        
        .news-item:last-child {
            border-bottom: none;
        }
        
        .news-title {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--kozter-blue-dark);
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }
        
        .news-title a {
            color: inherit;
            text-decoration: none;
        }
        
        .news-title a:hover {
            color: var(--kozter-blue);
        }
        
        /* KÖZÉPSŐ OSZLOP - Kategóriák */
        .category-column {
            border-right: 1px solid #E5E7EB;
            padding-right: 20px;
            padding-left: 20px;
        }
        
        .category-section-kozter {
            margin-bottom: 2rem;
        }
        
        .category-title-kozter {
            color: var(--kozter-blue-dark);
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .category-title-kozter a {
            color: inherit;
            text-decoration: none;
        }
        
        .category-title-kozter a:hover {
            color: var(--kozter-yellow-dark);
        }
        
        .category-featured {
            background: white;
            border-radius: 6px;
            padding: 1rem;
            border: 1px solid #F3F4F6;
        }
        
        .category-post-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--kozter-blue-dark);
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }
        
        .category-post-title a {
            color: inherit;
            text-decoration: none;
        }
        
        .category-post-title a:hover {
            color: var(--kozter-blue);
        }
        
        .category-excerpt {
            color: var(--kozter-gray);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .category-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #F9FAFB;
        }
        
        .category-item:last-child {
            border-bottom: none;
        }
        
        .category-item-title {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--kozter-blue-dark);
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }
        
        .category-item-title a {
            color: inherit;
            text-decoration: none;
        }
        
        .category-item-title a:hover {
            color: var(--kozter-blue);
        }
        
        .category-divider {
            border-color: var(--kozter-yellow-light);
            border-width: 2px;
            margin: 2rem 0;
        }
        
        /* JOBB OSZLOP - Sidebar */
        .sidebar-column {
            padding-left: 20px;
        }
        
        .sidebar-title {
            color: var(--kozter-blue-dark);
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .popular-item {
            display: flex;
            align-items: flex-start;
            padding: 0.75rem 0;
            border-bottom: 1px solid #F3F4F6;
        }
        
        .popular-item:last-child {
            border-bottom: none;
        }
        
        .popular-number {
            background: var(--kozter-yellow);
            color: var(--kozter-blue-dark);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }
        
        .popular-content {
            flex: 1;
        }
        
        .popular-title {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--kozter-blue-dark);
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }
        
        .popular-title a {
            color: inherit;
            text-decoration: none;
        }
        
        .popular-title a:hover {
            color: var(--kozter-blue);
        }
        
        .tag-item, .popular-overall-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #F9FAFB;
        }
        
        .tag-item:last-child, .popular-overall-item:last-child {
            border-bottom: none;
        }
        
        .tag-post-title, .popular-overall-title {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--kozter-blue-dark);
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }
        
        .tag-post-title a, .popular-overall-title a {
            color: inherit;
            text-decoration: none;
        }
        
        .tag-post-title a:hover, .popular-overall-title a:hover {
            color: var(--kozter-blue);
        }
        
        .support-card {
            background: linear-gradient(135deg, var(--kozter-yellow-light) 0%, var(--kozter-yellow) 100%);
            border-radius: 8px;
            text-align: center;
        }
        
        .support-title {
            color: var(--kozter-blue-dark);
            font-weight: 700;
            margin-bottom: 0.75rem;
        }
        
        .support-text {
            color: var(--kozter-blue-dark);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .quick-links-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .quick-links-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #F3F4F6;
        }
        
        .quick-links-list li:last-child {
            border-bottom: none;
        }
        
        .quick-links-list a {
            color: var(--kozter-blue-dark);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .quick-links-list a:hover {
            color: var(--kozter-blue);
        }
        
        /* Meta információk */
        .post-meta-main {
            color: var(--kozter-gray);
            font-size: 0.8rem;
        }
        
        .post-meta-small {
            color: var(--kozter-gray);
            font-size: 0.75rem;
        }
        
        .post-meta-tiny {
            color: var(--kozter-gray);
            font-size: 0.7rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .main-column, .category-column {
                border-right: none;
                padding-right: 15px;
                margin-bottom: 2rem;
            }
            
            .sidebar-column {
                padding-left: 15px;
            }
            
            .kozter-layout {
                gap: 10px;
            }
        }
        
        @media (max-width: 768px) {
            .homepage-kozter {
                font-size: 13px;
            }
            
            .featured-title {
                font-size: 1.3rem;
            }
            
            .secondary-title, .category-post-title {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header class="site-header">
    <!-- 1. sor: bal dátum+névnap, középen mini menü, jobb oldalt hőmérséklet -->
    <div class="header-top py-2">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <span id="today-date"></span>
                <span class="text-muted">•</span>
                <span id="nameday"></span>
            </div>
            <div class="mini-menu d-none d-md-flex">
                <?= Html::a('Műsoraink', ['/site/index', '#' => 'musoraink']) ?>
                <?= Html::a('A drága olvasónak', ['/site/support']) ?>
                <?= Html::a('A drága sajtónak', ['/site/contact']) ?>
                <?= Html::a('Rólunk', ['/site/about']) ?>
                <?= Html::a('Választási térkép', ['/site/election-map']) ?>
            </div>
            <div class="weather text-nowrap">
                <i class="fa-solid fa-cloud-sun header-icon me-1"></i>
                <span id="weather-temp">--°C</span>
            </div>
        </div>
    </div>

    <!-- 2. sor: bal menü+keresőgomb, középen logo, jobb Támogatás -->
    <div class="header-second pt-2 pb-2">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-link p-2 text-decoration-none header-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTags" aria-controls="offcanvasTags" title="Menü">
                    <i class="fa-solid fa-bars fa-lg"></i>
            </button>
                <button class="btn btn-link p-2 text-decoration-none header-icon" type="button" data-bs-toggle="collapse" data-bs-target="#headerSearch" aria-expanded="false" aria-controls="headerSearch" title="Keresés">
                    <i class="fa-solid fa-magnifying-glass fa-lg"></i>
                            </button>
                        </div>
            <div class="logo-center text-center flex-grow-1">
                <a href="<?= Url::to(['/site/index']) ?>" aria-label="KözTér - nyitóoldal">
                    <img src="/imgs/kozter_logo_yellow.png" alt="KözTér" style="height:72px;" />
                </a>
                </div>
            <div class="text-end">
                <button class="btn support-btn" type="button" data-bs-toggle="collapse" data-bs-target="#headerSearch" aria-expanded="false" aria-controls="headerSearch">Támogatás</button>
            </div>
        </div>
    </div>

    <!-- Kereső sáv a header alatt -->
    <div class="collapse header-search-wrap" id="headerSearch">
        <div class="container-fluid py-3">
            <form action="<?= Url::to(['/post/index']) ?>" method="get" class="d-flex justify-content-center gap-2">
                <input type="text" name="q" class="form-control w-50" placeholder="Keresés a cikkek között...">
                <button class="btn btn-kozter btn-search" type="submit"><i class="fa-solid fa-magnifying-glass me-1"></i>Keresés</button>
            </form>
        </div>
    </div>

    <!-- Offcanvas: címkék balról előúszva -->
    <div class="offcanvas offcanvas-start offcanvas-tags" tabindex="-1" id="offcanvasTags" aria-labelledby="offcanvasTagsLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasTagsLabel">Címkék</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Bezárás"></button>
        </div>
        <div class="offcanvas-body">
            <?php
            // egyszerű címkelista (max 30) — a controller nélkül, közvetlen lekérdezéssel
            try {
                $tags = \common\models\Tag::find()->orderBy(['name' => SORT_ASC])->limit(30)->all();
            } catch (\Throwable $e) {
                $tags = [];
            }
            foreach ($tags as $tag) {
                echo Html::a('#' . Html::encode($tag->name), ['/post/tag', 'slug' => $tag->slug ?? $tag->id]);
            }
            ?>
        </div>
    </div>
</header>

<main role="main" class="flex-shrink-0" style="padding-top: 0;">
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer-kozter mt-auto pt-5 pb-4">
    <div class="container">
        <div class="row gy-4 align-items-start">
            <div class="col-lg-6 d-flex align-items-start gap-3">
                <a href="<?= Url::to(['/site/index']) ?>" class="me-2" aria-label="Kezdőlap">
                    <img src="/imgs/kozter_logo_yellow.png" alt="KözTér" style="height:120px;" />
                </a>
                <nav class="footer-nav d-flex flex-column">
                    <a href="<?= Url::to(['/site/support']) ?>">Támogass</a>
                    <a href="<?= Url::to(['/site/index']) . '#musoraink' ?>">Műsoraink</a>
                    <a href="<?= Url::to(['/site/support']) ?>">A drága olvasónak</a>
                    <a href="<?= Url::to(['/site/contact']) ?>">A drága sajtónak</a>
                    <a href="<?= Url::to(['/site/about']) ?>">Rólunk</a>
                    <a href="<?= Url::to(['/site/election-map']) ?>">Választási térkép</a>
                </nav>
            </div>
            <div class="col-lg-4 offset-lg-2">
                <div class="footer-title mb-3">Kövess minket</div>
                <div class="footer-social mb-4">
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                </div>
                <div class="footer-title mb-2">Kapcsolat</div>
                <a class="footer-contact" href="mailto:info@kozter.com">info@kozter.com</a>
            </div>
        </div>
        <div class="footer-bottom mt-5 pt-3">
            <div class="d-flex flex-wrap justify-content-between">
                <p class="mb-0 small"><a href="https://kozter.com" target="_blank" rel="noopener">kozter.com</a> &copy; <?= date('Y') ?>. Minden jog fenntartva.</p>
                <p class="mb-0 small">Fejlesztő: <span class="text-warning">Web Solutions Hungary Kft.</span></p>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
<!-- Scroll gomb jobb alsó sarokban, kör progresszel -->
<button id="scrollProgressBtn" class="scroll-progress-btn" aria-label="Ugrás az oldal tetejére">
    <svg viewBox="0 0 50 50" aria-hidden="true" focusable="false">
        <circle class="scroll-progress-track" cx="25" cy="25" r="22"></circle>
        <circle class="scroll-progress-bar" cx="25" cy="25" r="22"></circle>
        <polyline class="scroll-progress-arrow" points="18,28 25,21 32,28"></polyline>
    </svg>
</button>
<script>
    // Dátum + névnap (egyszerű placeholder névnap szöveggel)
    (function(){
        const d = new Date();
        const pad = n => (n<10?'0':'')+n;
        const months = ['jan','feb','márc','ápr','máj','jún','júl','aug','szept','okt','nov','dec'];
        const days = ['vasárnap','hétfő','kedd','szerda','csütörtök','péntek','szombat'];
        const dateStr = `${d.getFullYear()}. ${pad(d.getMonth()+1)}. ${pad(d.getDate())}., ${days[d.getDay()]}`;
        const el = document.getElementById('today-date');
        if (el) el.textContent = dateStr;
        const nameday = document.getElementById('nameday');
        if (nameday) nameday.textContent = 'Névnap: n.a.';
    })();
    // Hőmérséklet placeholder (később API-ra cserélhető)
    (function(){
        const t = document.getElementById('weather-temp');
        if (t) t.textContent = '12°C';
    })();
    // Scroll progress kör és felgörgetés
    (function(){
        const btn = document.getElementById('scrollProgressBtn');
        if (!btn) return;
        const bar = btn.querySelector('.scroll-progress-bar');
        const circumference = 2 * Math.PI * 22; // r=22

        function update() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
            const docHeight = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
            const winHeight = window.innerHeight || document.documentElement.clientHeight;
            const maxScroll = Math.max(docHeight - winHeight, 0);
            const ratio = maxScroll > 0 ? Math.min(scrollTop / maxScroll, 1) : 0;
            const offset = circumference * (1 - ratio);
            bar.style.strokeDasharray = String(circumference);
            bar.style.strokeDashoffset = String(offset);
            // gomb megjelenítése csak ha van hova görgetni és már lejjebb vagyunk
            if (scrollTop > 80) btn.classList.add('show'); else btn.classList.remove('show');
        }

        window.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        document.addEventListener('DOMContentLoaded', update);
        update();

        btn.addEventListener('click', function(e){
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    })();
 </script>
</body>
</html>
<?php $this->endPage();
