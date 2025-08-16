<?php

/** @var yii\web\View $this */
/** @var array $userStats */
/** @var array $categoryStats */
/** @var array $tagStats */
/** @var array $mediaStats */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Adminisztrációs Panel';

// Segédfüggvény a fájlméret formázásához
function formatFileSize($bytes) {
    if (!$bytes) return '0 B';
    $units = ['B', 'KB', 'MB', 'GB'];
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}
?>
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="row row-cards">
            <!-- Felhasználók statisztika -->
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    <?= Html::a($userStats['total'] . ' Felhasználó', ['/user/index'], ['class' => 'text-decoration-none']) ?>
                                </div>
                                <div class="text-muted">
                                    <span class="badge bg-success-lt me-1"><?= $userStats['active'] ?> aktív</span>
                                    <span class="badge bg-warning-lt"><?= $userStats['recent'] ?> új (7 nap)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Kategóriák statisztika -->
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-green text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.5 2a1.5 1.5 0 0 1 1.5 1.5v6a1.5 1.5 0 0 1 -1.5 1.5h-6a1.5 1.5 0 0 1 -1.5 -1.5v-6a1.5 1.5 0 0 1 1.5 -1.5h6"/><path d="M20.5 13a1.5 1.5 0 0 1 1.5 1.5v6a1.5 1.5 0 0 1 -1.5 1.5h-6a1.5 1.5 0 0 1 -1.5 -1.5v-6a1.5 1.5 0 0 1 1.5 -1.5h6"/><path d="M9.5 13a1.5 1.5 0 0 1 1.5 1.5v6a1.5 1.5 0 0 1 -1.5 1.5h-6a1.5 1.5 0 0 1 -1.5 -1.5v-6a1.5 1.5 0 0 1 1.5 -1.5h6"/><path d="M20.5 2a1.5 1.5 0 0 1 1.5 1.5v6a1.5 1.5 0 0 1 -1.5 1.5h-6a1.5 1.5 0 0 1 -1.5 -1.5v-6a1.5 1.5 0 0 1 1.5 -1.5h6"/></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    <?= Html::a($categoryStats['total'] . ' Kategória', ['/category/index'], ['class' => 'text-decoration-none']) ?>
                                </div>
                                <div class="text-muted">
                                    <span class="badge bg-success-lt me-1"><?= $categoryStats['active'] ?> aktív</span>
                                    <span class="badge bg-warning-lt"><?= $categoryStats['recent'] ?> új (7 nap)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Címkék statisztika -->
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-azure text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"/><path d="M3 6v5.172a2 2 0 0 0 .586 1.414l7.71 7.71a2.41 2.41 0 0 0 3.408 0l5.592 -5.592a2.41 2.41 0 0 0 0 -3.408l-7.71 -7.71a2 2 0 0 0 -1.414 -.586h-5.172a3 3 0 0 0 -3 3z"/></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    <?= Html::a($tagStats['total'] . ' Címke', ['/tag/index'], ['class' => 'text-decoration-none']) ?>
                                </div>
                                <div class="text-muted">
                                    <span class="badge bg-success-lt me-1"><?= $tagStats['active'] ?> aktív</span>
                                    <span class="badge bg-warning-lt"><?= $tagStats['recent'] ?> új (7 nap)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Média statisztika -->
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-orange text-white avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 8h.01"/><path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z"/><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5"/><path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3"/></svg>
                                </span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">
                                    <?= Html::a($mediaStats['total'] . ' Média', ['/media/index'], ['class' => 'text-decoration-none']) ?>
                                </div>
                                <div class="text-muted">
                                    <span class="badge bg-success-lt me-1"><?= $mediaStats['images'] ?> kép</span>
                                    <span class="badge bg-info-lt"><?= formatFileSize($mediaStats['totalSize']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- További részletes statisztikák -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Gyors hozzáférés</h3>
                <div class="card-actions">
                    <span class="text-muted">Kattints a kártyákra a részletes információkért</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-link card-link-pop">
                            <div class="card-body text-center">
                                <div class="card-title mb-3">
                                    <span class="avatar avatar-lg bg-primary-lt mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2l3.09 6.26l6.91 1.01l-5 4.87l1.18 6.88l-6.18 -3.25l-6.18 3.25l1.18 -6.88l-5 -4.87l6.91 -1.01z"/></svg>
                                    </span>
                                    <br>
                                    <?= Html::a('Új felhasználó', ['/user/create'], ['class' => 'btn btn-primary btn-sm stretched-link']) ?>
                                </div>
                                <div class="text-muted">Gyorsan adj hozzá új felhasználót a rendszerhez</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-link card-link-pop">
                            <div class="card-body text-center">
                                <div class="card-title mb-3">
                                    <span class="avatar avatar-lg bg-success-lt mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                                    </span>
                                    <br>
                                    <?= Html::a('Új kategória', ['/category/create'], ['class' => 'btn btn-success btn-sm stretched-link']) ?>
                                </div>
                                <div class="text-muted">Hozz létre új kategóriát a tartalom szervezéséhez</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-link card-link-pop">
                            <div class="card-body text-center">
                                <div class="card-title mb-3">
                                    <span class="avatar avatar-lg bg-azure-lt mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                                    </span>
                                    <br>
                                    <?= Html::a('Új címke', ['/tag/create'], ['class' => 'btn btn-azure btn-sm stretched-link']) ?>
                                </div>
                                <div class="text-muted">Adj hozzá új címkét a tartalom címkézéséhez</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-link card-link-pop">
                            <div class="card-body text-center">
                                <div class="card-title mb-3">
                                    <span class="avatar avatar-lg bg-orange-lt mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/><path d="M12 17v-6"/><path d="M9.5 14.5l2.5 2.5l2.5 -2.5"/></svg>
                                    </span>
                                    <br>
                                    <?= Html::a('Média feltöltés', ['/media/create'], ['class' => 'btn btn-orange btn-sm stretched-link']) ?>
                                </div>
                                <div class="text-muted">Tölts fel új médiafájlokat a rendszerbe</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
