<?php
use yii\helpers\Html;

$this->title = 'Választási térkép';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-election-map py-4">
    <h1 class="mb-3"><?= Html::encode($this->title) ?></h1>
    <p class="text-muted mb-4">Interaktív választási térkép – hamarosan.</p>
    <div class="bg-white p-4 rounded" style="min-height:300px;border:1px solid #E5E7EB;">
        <!-- Ide kerül az interaktív térkép integrációja -->
        <div class="text-center text-secondary">Fejlesztés alatt</div>
    </div>
</div>


