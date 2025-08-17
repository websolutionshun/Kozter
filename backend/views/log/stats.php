<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $stats */
/** @var array $levels */

$this->title = 'Log statisztikák';
$this->params['breadcrumbs'][] = ['label' => 'Rendszerlogok', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="log-stats">
    <!-- Összesítő kártyák -->
    <div class="row mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Összes log</div>
                        <div class="ms-auto lh-1">
                            <div class="dropdown">
                                <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/><circle cx="12" cy="5" r="1"/></svg>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <?= Html::a('Összes log megtekintése', ['index'], ['class' => 'dropdown-item']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="h1 mb-3"><?= number_format($stats['total']) ?></div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Mai logok</div>
                        <div class="ms-auto lh-1">
                            <div class="dropdown">
                                <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/><circle cx="12" cy="5" r="1"/></svg>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <?= Html::a('Mai logok megtekintése', ['index', 'date_filter' => 'today'], ['class' => 'dropdown-item']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="h1 mb-3"><?= number_format($stats['today']) ?></div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Hibák száma</div>
                        <div class="ms-auto lh-1">
                            <div class="dropdown">
                                <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/><circle cx="12" cy="5" r="1"/></svg>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <?= Html::a('Hibák megtekintése', ['index', 'level' => 'error'], ['class' => 'dropdown-item']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="h1 mb-3 text-red"><?= number_format($stats['levels']['error'] ?? 0) ?></div>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Figyelmeztetések</div>
                        <div class="ms-auto lh-1">
                            <div class="dropdown">
                                <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/><circle cx="12" cy="5" r="1"/></svg>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <?= Html::a('Figyelmeztetések megtekintése', ['index', 'level' => 'warning'], ['class' => 'dropdown-item']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="h1 mb-3 text-yellow"><?= number_format($stats['levels']['warning'] ?? 0) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Szint szerinti statisztika -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 12h4l3 8l4 -16l3 8h4"/>
                        </svg>
                        Szint szerinti megoszlás
                    </h3>
                </div>
                <div class="card-body">
                    <div class="chart-lg">
                        <?php 
                        $total = array_sum($stats['levels']);
                        foreach ($levels as $level => $label): 
                            $count = $stats['levels'][$level] ?? 0;
                            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                            
                            $colorClasses = [
                                'error' => 'bg-red',
                                'warning' => 'bg-yellow', 
                                'info' => 'bg-blue',
                                'success' => 'bg-green',
                            ];
                            $colorClass = $colorClasses[$level] ?? 'bg-secondary';
                        ?>
                        <div class="row align-items-center mb-3">
                            <div class="col-auto">
                                <span class="legend-dot <?= $colorClass ?>"></span>
                            </div>
                            <div class="col">
                                <div class="fw-medium"><?= Html::encode($label) ?></div>
                                <div class="text-muted"><?= number_format($count) ?> bejegyzés (<?= $percentage ?>%)</div>
                            </div>
                            <div class="col-auto">
                                <?= Html::a('Megtekintés', ['index', 'level' => $level], [
                                    'class' => 'btn btn-sm btn-outline-primary'
                                ]) ?>
                            </div>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar <?= $colorClass ?>" style="width: <?= $percentage ?>%" 
                                 role="progressbar" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kategória szerinti statisztika -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 4h6l3 7h-12l3 -7z"/>
                            <path d="M9 4v10a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2v-10"/>
                            <path d="M15 4v10a2 2 0 0 0 2 2h2a2 2 0 0 0 2 -2v-10"/>
                        </svg>
                        Top kategóriák
                    </h3>
                </div>
                <div class="card-body">
                    <?php if (empty($stats['categories'])): ?>
                        <div class="text-muted text-center py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M9 12l2 2l4 -4"/>
                            </svg>
                            <div>Nincsenek kategorizált logok</div>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php 
                            $maxCount = max(array_column($stats['categories'], 'count'));
                            foreach ($stats['categories'] as $category): 
                                $percentage = $maxCount > 0 ? round(($category['count'] / $maxCount) * 100, 1) : 0;
                            ?>
                            <div class="list-group-item d-flex align-items-center px-0">
                                <div class="flex-fill">
                                    <div class="fw-medium"><?= Html::encode($category['category'] ?: 'Nincs kategória') ?></div>
                                    <div class="text-muted small"><?= number_format($category['count']) ?> bejegyzés</div>
                                </div>
                                <div class="ms-auto">
                                    <?= Html::a('Megtekintés', ['index', 'category' => $category['category']], [
                                        'class' => 'btn btn-sm btn-outline-primary'
                                    ]) ?>
                                </div>
                            </div>
                            <div class="progress mb-2" style="height: 4px;">
                                <div class="progress-bar bg-primary" style="width: <?= $percentage ?>%" 
                                     role="progressbar" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Napi statisztika -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 12h4l3 8l4 -16l3 8h4"/>
                        </svg>
                        Heti aktivitás (utolsó 7 nap)
                    </h3>
                </div>
                <div class="card-body">
                    <div class="chart-lg">
                        <?php 
                        $maxDailyCount = max(array_column($stats['daily'], 'count'));
                        if ($maxDailyCount == 0) $maxDailyCount = 1;
                        ?>
                        <div class="row">
                            <?php foreach ($stats['daily'] as $day): 
                                $percentage = round(($day['count'] / $maxDailyCount) * 100, 1);
                                $formattedDate = date('M j', strtotime($day['date']));
                                $dayName = date('D', strtotime($day['date']));
                            ?>
                            <div class="col text-center">
                                <div class="chart-value mb-3" style="height: 150px; display: flex; flex-direction: column; justify-content: end;">
                                    <div class="bg-primary rounded" style="height: <?= $percentage ?>%; min-height: 2px; width: 30px; margin: 0 auto;">
                                    </div>
                                </div>
                                <div class="chart-label">
                                    <div class="fw-medium"><?= $day['count'] ?></div>
                                    <div class="text-muted small"><?= $formattedDate ?></div>
                                    <div class="text-muted small"><?= $dayName ?></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visszatérés gomb -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-start">
                <?= Html::a('Vissza a logokhoz', ['index'], [
                    'class' => 'btn btn-primary'
                ]) ?>
            </div>
        </div>
    </div>
</div>
