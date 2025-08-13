<?php

namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Breadcrumb widget magyar admin felülethez
 * 
 * Használat:
 * <?= Breadcrumb::widget([
 *     'items' => [
 *         ['label' => 'Főoldal', 'url' => ['/site/index']],
 *         ['label' => 'Felhasználók', 'url' => ['/user/index']],
 *         'Új felhasználó'
 *     ]
 * ]) ?>
 */
class Breadcrumb extends Widget
{
    /**
     * @var array breadcrumb elemek listája
     */
    public $items = [];

    /**
     * @var string wrapper tag
     */
    public $tag = 'nav';

    /**
     * @var array wrapper HTML attribútumok
     */
    public $options = ['aria-label' => 'breadcrumb'];

    /**
     * @var array breadcrumb lista HTML attribútumok
     */
    public $listOptions = ['class' => 'breadcrumb'];

    /**
     * @var string otthon ikon
     */
    public $homeIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0"/><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"/></svg>';

    /**
     * @var bool automatikusan hozzáadja a főoldalt
     */
    public $includeHome = true;

    /**
     * @var string főoldal címkéje
     */
    public $homeLabel = 'Főoldal';

    /**
     * @var array főoldal URL
     */
    public $homeUrl = ['/site/index'];

    /**
     * Widget futtatása
     */
    public function run()
    {
        if (empty($this->items)) {
            return '';
        }

        $items = $this->items;
        
        // Főoldal automatikus hozzáadása
        if ($this->includeHome) {
            array_unshift($items, [
                'label' => $this->homeIcon . ' ' . $this->homeLabel,
                'url' => $this->homeUrl,
                'encode' => false
            ]);
        }

        $breadcrumbItems = [];
        $itemCount = count($items);
        
        foreach ($items as $index => $item) {
            $isLast = ($index === $itemCount - 1);
            
            if (is_string($item)) {
                // Utolsó elem (aktuális oldal)
                $breadcrumbItems[] = Html::tag('li', 
                    Html::tag('span', Html::encode($item), ['class' => 'breadcrumb-item-label']),
                    ['class' => 'breadcrumb-item active', 'aria-current' => 'page']
                );
            } elseif (is_array($item)) {
                $label = $item['label'] ?? '';
                $encode = $item['encode'] ?? true;
                
                if ($isLast || !isset($item['url'])) {
                    // Utolsó elem vagy URL nélküli elem
                    $breadcrumbItems[] = Html::tag('li', 
                        Html::tag('span', $encode ? Html::encode($label) : $label, ['class' => 'breadcrumb-item-label']),
                        ['class' => 'breadcrumb-item active', 'aria-current' => 'page']
                    );
                } else {
                    // Link elem
                    $url = is_array($item['url']) ? Url::to($item['url']) : $item['url'];
                    $linkContent = $encode ? Html::encode($label) : $label;
                    
                    $breadcrumbItems[] = Html::tag('li', 
                        Html::a($linkContent, $url, ['class' => 'breadcrumb-link']),
                        ['class' => 'breadcrumb-item']
                    );
                }
            }
        }

        $breadcrumb = Html::tag('ol', implode("\n", $breadcrumbItems), $this->listOptions);
        
        return Html::tag($this->tag, $breadcrumb, $this->options);
    }
}
