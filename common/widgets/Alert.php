<?php

namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Alert extends Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - key: the name of the session flash variable
     * - value: the alert CSS class type (compatible with Tabler and Bootstrap)
     */
    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];
    
    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [
        'class' => 'btn-close',
        'data-bs-dismiss' => 'alert',
        'aria-label' => 'Close'
    ];


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $flash) {
            if (!isset($this->alertTypes[$type])) {
                continue;
            }

            foreach ((array) $flash as $i => $message) {
                $alertId = $this->getId() . '-' . $type . '-' . $i;
                $alertClass = 'alert alert-dismissible ' . $this->alertTypes[$type];
                
                echo Html::beginTag('div', [
                    'id' => $alertId,
                    'class' => $alertClass,
                    'role' => 'alert'
                ]);
                
                echo Html::encode($message);
                
                // Close button
                echo Html::button('', array_merge([
                    'type' => 'button',
                    'class' => 'btn-close',
                    'data-bs-dismiss' => 'alert',
                    'aria-label' => 'Close'
                ], $this->closeButton));
                
                echo Html::endTag('div');
            }

            $session->removeFlash($type);
        }
    }
}
