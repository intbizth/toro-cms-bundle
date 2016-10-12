<?php

namespace Toro\Bundle\CmsBundle\Model;

/**
 * Class OptionAwareTrait
 * @package Toro\Bundle\CmsBundle\Model
 * 
 * E01: Optionable should working by OptionAware Object (eg. PageOption) mapping one-to-on
 * But no working one-to-one on single-table mapping, we need to call `setOptionable` by manual within `optionable` model.
 */
trait OptionAwareTrait
{
    protected $optionable;

    /**
     * @return OptionableInterface
     */
    public function getOptionable()
    {
        return $this->optionable;
    }

    /**
     * @param OptionableInterface|null $optionable
     */
    public function setOptionable(OptionableInterface $optionable = null)
    {
        $this->optionable = $optionable;
    }
}
