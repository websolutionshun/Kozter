<?php

use yii\helpers\Html;

/** @var \yii\web\View $this view component instance */
/** @var \yii\mail\MessageInterface $message the message being composed */
/** @var string $content main view render result */

?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.5;
            color: #1f2937;
            background-color: #f8fafc;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .email-logo {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            text-decoration: none;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-footer {
            background-color: #f1f5f9;
            padding: 20px 30px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .btn:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        }
        h1 {
            color: #1f2937;
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 20px 0;
        }
        p {
            margin: 0 0 16px 0;
            color: #4b5563;
        }
        .highlight {
            background-color: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            padding: 16px;
            margin: 20px 0;
            border-radius: 0 6px 6px 0;
        }
    </style>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="email-container">
        <div class="email-header">
            <h2 class="email-logo"><?= Html::encode(Yii::$app->name) ?></h2>
        </div>
        <div class="email-body">
            <?= $content ?>
        </div>
        <div class="email-footer">
            <p>Ez egy automatikus email. Kérjük, ne válaszoljon erre az üzenetre.</p>
            <p>&copy; <?= date('Y') ?> <?= Html::encode(Yii::$app->name) ?>. Minden jog fenntartva.</p>
        </div>
    </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
