<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $urls array */
/* @var $sitemapPath string */
/* @var $fileSize int */
/* @var $lastModified int */

$this->title = 'Sitemap Tartalom';
$this->params['breadcrumbs'][] = ['label' => 'Sitemap Kezelő', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ArrayDataProvider([
    'allModels' => $urls,
    'sort' => [
        'attributes' => ['loc', 'lastmod', 'changefreq', 'priority'],
    ],
    'pagination' => [
        'pageSize' => 50,
    ],
]);
?>

<div class="sitemap-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <div>
            <?= Html::a('Vissza', ['index'], ['class' => 'btn btn-secondary me-2']) ?>
            
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
    </div>

    <!-- Fájl információk -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Fájl információk
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Fájl:</strong><br>
                            <code><?= Html::encode(basename($sitemapPath)) ?></code>
                        </div>
                        <div class="col-md-3">
                            <strong>Méret:</strong><br>
                            <?= Yii::$app->formatter->asShortSize($fileSize) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Utolsó módosítás:</strong><br>
                            <?= Yii::$app->formatter->asDatetime($lastModified) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>URL-ek száma:</strong><br>
                            <span class="badge bg-primary"><?= count($urls) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- URL-ek táblázata -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-link me-2"></i>Sitemap URL-ek
            </h5>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-hover mb-0'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    [
                        'attribute' => 'loc',
                        'label' => 'URL',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $url = $model['loc'];
                            $label = $url;
                            
                            // URL típus meghatározása badge-dzsel
                            $badge = '';
                            if (strpos($url, '/blog/') !== false && $url !== rtrim(Yii::$app->params['frontendUrl'], '/') . '/blog') {
                                $badge = ' <span class="badge bg-info text-white ms-2">Blog bejegyzés</span>';
                                $label = str_replace(rtrim(Yii::$app->params['frontendUrl'], '/'), '', $url);
                            } elseif (strpos($url, '/blog') !== false) {
                                $badge = ' <span class="badge bg-success text-white ms-2">Blog főoldal</span>';
                                $label = str_replace(rtrim(Yii::$app->params['frontendUrl'], '/'), '', $url);
                            } else {
                                $badge = ' <span class="badge bg-secondary text-white ms-2">Statikus</span>';
                                $label = str_replace(rtrim(Yii::$app->params['frontendUrl'], '/'), '', $url);
                                if (empty($label)) $label = '/';
                            }
                            
                            return Html::a(Html::encode($label), $url, [
                                'target' => '_blank',
                                'class' => 'text-decoration-none'
                            ]) . $badge;
                        },
                    ],
                    [
                        'attribute' => 'lastmod',
                        'label' => 'Utolsó módosítás',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (empty($model['lastmod'])) {
                                return '<span class="text-muted">-</span>';
                            }
                            
                            $timestamp = strtotime($model['lastmod']);
                            return '<span title="' . Yii::$app->formatter->asDatetime($timestamp) . '">' . 
                                   Yii::$app->formatter->asRelativeTime($timestamp) . '</span>';
                        },
                    ],
                    [
                        'attribute' => 'changefreq',
                        'label' => 'Változtatási gyakoriság',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $freq = $model['changefreq'];
                            $colors = [
                                'always' => 'danger',
                                'hourly' => 'warning',
                                'daily' => 'success',
                                'weekly' => 'info',
                                'monthly' => 'primary',
                                'yearly' => 'secondary',
                                'never' => 'dark',
                            ];
                            
                            $color = $colors[$freq] ?? 'light';
                            $textColor = in_array($color, ['warning', 'light']) ? 'dark' : 'white';
                            
                            return '<span class="badge bg-' . $color . ' text-' . $textColor . '">' . 
                                   Html::encode(ucfirst($freq)) . '</span>';
                        },
                    ],
                    [
                        'attribute' => 'priority',
                        'label' => 'Prioritás',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $priority = (float)$model['priority'];
                            
                            if ($priority >= 0.8) {
                                $class = 'bg-success text-white';
                            } elseif ($priority >= 0.6) {
                                $class = 'bg-info text-white';
                            } elseif ($priority >= 0.4) {
                                $class = 'bg-warning text-dark';
                            } else {
                                $class = 'bg-secondary text-white';
                            }
                            
                            return '<span class="badge ' . $class . '">' . $priority . '</span>';
                        },
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <!-- Összesítő statisztikák -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Összesítő
                    </h5>
                </div>
                <div class="card-body">
                    <?php
                    $stats = [
                        'changefreq' => [],
                        'priority' => [],
                        'type' => ['blog_post' => 0, 'blog_main' => 0, 'static' => 0],
                    ];
                    
                    foreach ($urls as $url) {
                        // Changefreq statisztika
                        $freq = $url['changefreq'];
                        $stats['changefreq'][$freq] = ($stats['changefreq'][$freq] ?? 0) + 1;
                        
                        // Priority statisztika  
                        $priority = $url['priority'];
                        $stats['priority'][$priority] = ($stats['priority'][$priority] ?? 0) + 1;
                        
                        // Type statisztika
                        $loc = $url['loc'];
                        if (strpos($loc, '/blog/') !== false && $loc !== rtrim(Yii::$app->params['frontendUrl'], '/') . '/blog') {
                            $stats['type']['blog_post']++;
                        } elseif (strpos($loc, '/blog') !== false) {
                            $stats['type']['blog_main']++;
                        } else {
                            $stats['type']['static']++;
                        }
                    }
                    ?>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <h6>URL típusok</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-circle text-info me-2"></i>Blog bejegyzések: <strong><?= $stats['type']['blog_post'] ?></strong></li>
                                <li><i class="fas fa-circle text-success me-2"></i>Blog főoldal: <strong><?= $stats['type']['blog_main'] ?></strong></li>
                                <li><i class="fas fa-circle text-secondary me-2"></i>Statikus oldalak: <strong><?= $stats['type']['static'] ?></strong></li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6>Változtatási gyakoriság</h6>
                            <ul class="list-unstyled">
                                <?php foreach ($stats['changefreq'] as $freq => $count): ?>
                                    <li><i class="fas fa-circle text-primary me-2"></i><?= ucfirst($freq) ?>: <strong><?= $count ?></strong></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6>Prioritás eloszlás</h6>
                            <ul class="list-unstyled">
                                <?php 
                                ksort($stats['priority']);
                                foreach ($stats['priority'] as $priority => $count): 
                                ?>
                                    <li><i class="fas fa-circle text-warning me-2"></i><?= $priority ?>: <strong><?= $count ?></strong></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
