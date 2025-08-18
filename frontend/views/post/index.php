<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = 'Bejegyzések';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-index">
    <div class="container">
        <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

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
