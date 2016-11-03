<?php

namespace Toro\Bundle\CmsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class ColorPickerTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        return $value ? '#'.$value : null;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return str_replace('#', '', $value);
    }
}
