<?php

/** @var yii\web\View $this */
/** @var common\models\Category $category */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = $category->name . ' - Bejegyzések';
$this->params['breadcrumbs'][] = ['label' => 'Bejegyzések', 'url' => ['index']];
$this->params['breadcrumbs'][] = $category->name;
?>

<div class="post-category">
    <div class="container">
        <div class="category-header mb-4">
            <h1 class="mb-2"><?= Html::encode($category->name) ?></h1>
            <?php if ($category->description): ?>
                <p class="lead text-muted"><?= Html::encode($category->description) ?></p>
            <?php endif; ?>
        </div>

        <?php Pjax::begin(); ?>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_post_item',
            'layout' => "{summary}\n<div class='row'>{items}</div>\n{pager}",
            'itemOptions' => ['class' => 'col-lg-6 mb-4'],
            'pager' => [
                'class' => 'yii\bootstrap5\LinkPager',
            ],
        ]); ?>

        <?php Pjax::end(); ?>
    </div>
</div>
