<?php

/*
 * This file is part of the Pagerfanta package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Toro\Bundle\CmsBundle\Pagerfanta\View\Template;

use Pagerfanta\View\Template\Template;

/**
 * TwitterBootstrap3Template
 */
class TwitterBootstrap4Template extends Template
{
    protected static $defaultOptions = array(
        'dots_message'        => '&hellip;',
        'active_suffix'       => '',
        'css_container_class' => 'pagination',
        'css_prev_class'      => 'prev',
        'css_next_class'      => 'next',
        'css_disabled_class'  => 'disabled',
        'css_dots_class'      => 'disabled',
        'css_active_class'    => 'active',
    );

    public function __construct()
    {
        parent::__construct();

        $this->setOptions(array('active_suffix' => '<span class="sr-only">(current)</span>' ));
    }


    public function container()
    {
        return sprintf('<div class="row"><div class="col-md-12"><ul class="%s">%%pages%%</ul></div></div>',
            $this->option('css_container_class')
        );
    }

    public function page($page)
    {
        $text = $page;

        return $this->pageWithText($page, $text);
    }

    public function pageWithText($page, $text)
    {
        $class = null;

        return $this->pageWithTextAndClass($page, $text, $class);
    }

    private function pageWithTextAndClass($page, $text, $class)
    {
        $href = $this->generateRoute($page);

        return $this->linkLi($class, $href, $text);
    }

    public function previousDisabled()
    {
        $class = $this->previousDisabledClass();
        $text = $this->option('prev_message');

        return $this->spanLi($class, $text);
    }

    private function previousDisabledClass()
    {
        return $this->option('css_prev_class').' '.$this->option('css_disabled_class');
    }

    public function previousEnabled($page)
    {
        $text = $this->option('prev_message');
        $class = $this->option('css_prev_class');

        return $this->pageWithTextAndClass($page, $text, $class);
    }

    public function nextDisabled()
    {
        $class = $this->nextDisabledClass();
        $text = $this->option('next_message');

        return $this->spanLi($class, $text);
    }

    private function nextDisabledClass()
    {
        return $this->option('css_next_class').' '.$this->option('css_disabled_class');
    }

    public function nextEnabled($page)
    {
        $text = $this->option('next_message');
        $class = $this->option('css_next_class');

        return $this->pageWithTextAndClass($page, $text, $class);
    }

    public function first()
    {
        return $this->page(1);
    }

    public function last($page)
    {
        return $this->page($page);
    }

    public function current($page)
    {
        $text = trim($page.' '.$this->option('active_suffix'));
        $class = $this->option('css_active_class');

        return $this->spanLi($class, $text);
    }

    public function separator()
    {
        $class = $this->option('css_dots_class');
        $text = $this->option('dots_message');

        return $this->spanLi($class, $text);
    }

    private function linkLi($class, $href, $text)
    {
        $liClass = $class ? sprintf(' class="page-item %s"', $class) : ' class="page-item"';

        return sprintf('<li%s><a class="page-link" href="%s">%s</a></li>', $liClass, $href, $text);
    }

    private function spanLi($class, $text)
    {
        $liClass = $class ? sprintf(' class="page-item %s"', $class) : ' class="page-item"';

        return sprintf('<li%s><a href="#" class="page-link"><span>%s</span></a></li>', $liClass, $text);
    }
}
