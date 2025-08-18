<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $sitemapExists bool */
/* @var $sitemapStats array|null */
/* @var $lastModified int|null */
/* @var $blogPostsCount int */
/* @var $totalPossibleUrls int */

$this->title = 'Sitemap Kezelő';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="sitemap-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <?php if (Yii::$app->user->identity && Yii::$app->user->identity->hasPermission('sitemap_generate')): ?>
            <?= Html::a('Sitemap újragenerálása', ['generate'], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Biztosan újragenerálod a sitemap-et?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </div>

    <?php Pjax::begin(); ?>

    <div class="row">
        <!-- Sitemap állapot kártya -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sitemap me-2"></i>Sitemap Állapot
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($sitemapExists): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Sitemap létezik és elérhető</strong>
                        </div>
                        
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Utolsó generálás:</strong></td>
                                <td>
                                    <?= Yii::$app->formatter->asDatetime($lastModified) ?>
                                    <small class="text-muted">
                                        (<?= Yii::$app->formatter->asRelativeTime($lastModified) ?>)
                                    </small>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Fájl helye:</strong></td>
                                <td><code>frontend/web/sitemap.xml</code></td>
                            </tr>
                            <tr>
                                <td><strong>Publikus URL:</strong></td>
                                <td>
                                    <?= Html::a(
                                        rtrim(Yii::$app->params['frontendUrl'], '/') . '/sitemap.xml',
                                        rtrim(Yii::$app->params['frontendUrl'], '/') . '/sitemap.xml',
                                        ['target' => '_blank', 'class' => 'text-decoration-none']
                                    ) ?>
                                    <i class="fas fa-external-link-alt ms-1"></i>
                                </td>
                            </tr>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Sitemap még nem lett generálva</strong>
                        </div>
                        <p class="text-muted">
                            Kattints a "Sitemap újragenerálása" gombra a sitemap létrehozásához.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Statisztikák kártya -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statisztikák
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($sitemapStats): ?>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border rounded p-3">
                                    <div class="h4 text-primary mb-1"><?= $sitemapStats['total'] ?></div>
                                    <small class="text-muted">Összes URL</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-3">
                                    <div class="h4 text-info mb-1"><?= $sitemapStats['blog'] ?></div>
                                    <small class="text-muted">Blog URL</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border rounded p-3">
                                    <div class="h4 text-success mb-1"><?= $sitemapStats['static'] ?></div>
                                    <small class="text-muted">Statikus URL</small>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-6">
                                <strong>Publikált blog bejegyzések:</strong>
                                <span class="badge bg-secondary"><?= $blogPostsCount ?></span>
                            </div>
                            <div class="col-6">
                                <strong>Lehetséges URL-ek:</strong>
                                <span class="badge bg-info"><?= $totalPossibleUrls ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Statisztikák elérhetőek lesznek a sitemap generálása után.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($sitemapExists): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Gyors áttekintés
                        </h5>
                        <?= Html::a('Részletes nézet', ['view'], [
                            'class' => 'btn btn-outline-primary btn-sm'
                        ]) ?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6>Blog bejegyzések</h6>
                                <ul class="list-unstyled small text-muted">
                                    <li><i class="fas fa-circle text-info me-2"></i>Változtatási gyakoriság: Heti</li>
                                    <li><i class="fas fa-circle text-info me-2"></i>Prioritás: 0.7</li>
                                    <li><i class="fas fa-circle text-info me-2"></i>URL: /blog/{slug}</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6>Blog főoldal</h6>
                                <ul class="list-unstyled small text-muted">
                                    <li><i class="fas fa-circle text-success me-2"></i>Változtatási gyakoriság: Napi</li>
                                    <li><i class="fas fa-circle text-success me-2"></i>Prioritás: 0.8</li>
                                    <li><i class="fas fa-circle text-success me-2"></i>URL: /blog</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6>Statikus oldalak</h6>
                                <ul class="list-unstyled small text-muted">
                                    <li><i class="fas fa-circle text-primary me-2"></i>Változtatási gyakoriság: Napi</li>
                                    <li><i class="fas fa-circle text-primary me-2"></i>Prioritás: 0.8-1.0</li>
                                    <li><i class="fas fa-circle text-primary me-2"></i>9 statikus oldal</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php Pjax::end(); ?>
</div>
