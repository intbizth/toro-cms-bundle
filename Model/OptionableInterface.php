<?php
namespace Toro\Bundle\CmsBundle\Model;

interface OptionableInterface
{
    /**
     * @return OptionInterface|OptionInterface
     */
    public function getOptions();

    /**
     * @param OptionInterface|null $options
     */
    public function setOptions(OptionInterface $options = null);
}
