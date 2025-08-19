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
    
    <!-- Google Fonts - Kozter.com stílusú betűtípus -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">
    
    <!-- Kozter színvilág CSS -->
    <style>
        :root {
            --kozter-yellow: #FFD700;
            --kozter-yellow-light: #FFF89A;
            --kozter-yellow-dark: #E6C200;
            --kozter-blue: #1E3A8A;
            --kozter-blue-light: #3B82F6;
            --kozter-blue-dark: #1E40AF;
            --kozter-green: #059669;
            --kozter-green-light: #10B981;
            --kozter-gray: #6B7280;
            --kozter-gray-light: #F3F4F6;
            --kozter-gray-dark: #374151;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #FAFAFA;
            color: var(--kozter-gray-dark);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Merriweather', Georgia, serif;
            font-weight: 700;
            color: var(--kozter-blue-dark);
        }
        
        .navbar-kozter {
            background: linear-gradient(135deg, var(--kozter-yellow) 0%, var(--kozter-yellow-light) 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-bottom: 3px solid var(--kozter-blue);
        }
        
        .navbar-kozter .navbar-brand {
            font-family: 'Merriweather', Georgia, serif;
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
            background: var(--kozter-gray-dark);
            color: white;
        }
        
        .footer-kozter a {
            color: var(--kozter-yellow-light);
        }
        
        .footer-kozter a:hover {
            color: var(--kozter-yellow);
        }
        
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
            margin-top: 2rem;
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
        
        /* TELEX-STÍLUSÚ 3 OSZLOPOS LAYOUT */
        .homepage-telex {
            font-size: 14px;
            line-height: 1.4;
        }
        
        .telex-layout {
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
        
        .category-section-telex {
            margin-bottom: 2rem;
        }
        
        .category-title-telex {
            color: var(--kozter-blue-dark);
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .category-title-telex a {
            color: inherit;
            text-decoration: none;
        }
        
        .category-title-telex a:hover {
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
            
            .telex-layout {
                gap: 10px;
            }
        }
        
        @media (max-width: 768px) {
            .homepage-telex {
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

<header>
    <nav class="navbar navbar-expand-lg navbar-kozter">
        <div class="container">
            <?= Html::a('KözTér', Yii::$app->homeUrl, ['class' => 'navbar-brand']) ?>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="color: var(--kozter-blue-dark);">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <?= Html::a('Főoldal', ['/site/index'], ['class' => 'nav-link']) ?>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Támogass
                        </a>
                        <ul class="dropdown-menu">
                            <li><?= Html::a('Támogatás módjai', ['/site/support'], ['class' => 'dropdown-item']) ?></li>
                            <li><?= Html::a('Patrons', ['/site/patrons'], ['class' => 'dropdown-item']) ?></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Műsoraink
                        </a>
                        <ul class="dropdown-menu">
                            <li><?= Html::a('Podcastok', ['/site/podcasts'], ['class' => 'dropdown-item']) ?></li>
                            <li><?= Html::a('Videók', ['/site/videos'], ['class' => 'dropdown-item']) ?></li>
                            <li><?= Html::a('Élő adások', ['/site/live'], ['class' => 'dropdown-item']) ?></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <?= Html::a('Bejegyzések', ['/post/index'], ['class' => 'nav-link']) ?>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            A drága olvasónak
                        </a>
                        <ul class="dropdown-menu">
                            <li><?= Html::a('Kapcsolat', ['/site/contact'], ['class' => 'dropdown-item']) ?></li>
                            <li><?= Html::a('GYIK', ['/site/faq'], ['class' => 'dropdown-item']) ?></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <?= Html::a('A drága sajtónak', ['/site/press'], ['class' => 'nav-link']) ?>
                    </li>
                    <li class="nav-item">
                        <?= Html::a('Rólunk', ['/site/about'], ['class' => 'nav-link']) ?>
                    </li>
                </ul>
                
                <div class="d-flex">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <?= Html::a('Bejelentkezés', ['/site/login'], ['class' => 'btn btn-kozter me-2']) ?>
                        <?= Html::a('Regisztráció', ['/site/signup'], ['class' => 'btn btn-outline-primary']) ?>
                    <?php else: ?>
                        <div class="dropdown">
                            <button class="btn btn-kozter dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <?= Html::encode(Yii::$app->user->identity->username ?? 'Felhasználó') ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><?= Html::a('Profil', ['/site/profile'], ['class' => 'dropdown-item']) ?></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <?= Html::beginForm(['/site/logout'], 'post') ?>
                                    <?= Html::submitButton('Kijelentkezés', ['class' => 'dropdown-item']) ?>
                                    <?= Html::endForm() ?>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<main role="main" class="flex-shrink-0" style="padding-top: 20px;">
    <div class="container-fluid">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer-kozter mt-auto py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5 class="text-white mb-3">KözTér</h5>
                <p class="mb-2">Független közéleti platform a szabad véleménynyilvánításért.</p>
                <div class="social-links">
                    <a href="#" class="me-3"><i class="fab fa-facebook"></i> Facebook</a>
                    <a href="#" class="me-3"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="#" class="me-3"><i class="fab fa-youtube"></i> YouTube</a>
                </div>
            </div>
            <div class="col-md-3">
                <h6 class="text-white mb-3">Hasznos linkek</h6>
                <ul class="list-unstyled">
                    <li><a href="<?= Url::to(['/site/about']) ?>">Rólunk</a></li>
                    <li><a href="<?= Url::to(['/site/contact']) ?>">Kapcsolat</a></li>
                    <li><a href="<?= Url::to(['/site/privacy']) ?>">Adatvédelem</a></li>
                    <li><a href="<?= Url::to(['/site/terms']) ?>">ÁSZF</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="text-white mb-3">Támogatás</h6>
                <ul class="list-unstyled">
                    <li><a href="<?= Url::to(['/site/support']) ?>">Támogass minket</a></li>
                    <li><a href="<?= Url::to(['/site/patrons']) ?>">Támogatóink</a></li>
                    <li><a href="<?= Url::to(['/site/donate']) ?>">Adományozás</a></li>
                </ul>
            </div>
        </div>
        <hr class="my-4" style="border-color: #6B7280;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; <?= date('Y') ?> <?= Html::encode(Yii::$app->name) ?>. Minden jog fenntartva.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">Fejlesztő: <span class="text-warning">Web Solutions Hungary Kft.</span></p>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
