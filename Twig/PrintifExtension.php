<?php

namespace Toro\Bundle\CmsBundle\Twig;

class PrintifExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('printif', array($this, 'printif')),
        );
    }

    /**
     * @param string|array $text | [true-text, false-text]
     * @param bool $condition
     *
     * @return string
     */
    public function printif($text, $condition)
    {
        $text = (array) $text;

        return @$text[intval(!$condition)];
    }
}
