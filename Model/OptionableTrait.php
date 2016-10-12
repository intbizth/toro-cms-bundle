<?php

namespace Toro\Bundle\CmsBundle\Model;

trait OptionableTrait
{
    /**
     * @var OptionInterface|OptionInterface
     */
    protected $options;

    /**
     * @return OptionInterface|OptionInterface
     */
    public function getOptions()
    {
        // Fix E01: for one-to-one mapping not work.
        // this.options will loaded by doctrine proxy class
        if ($this->options) {
            $this->options->setOptionable($this);
        }

        return $this->options;
    }

    /**
     * @param OptionInterface|null $options
     */
    public function setOptions(OptionInterface $options = null)
    {
        $this->options = $options;

        // Fix E01
        if ($options) {
            $options->setOptionable($this);
        }
    }
}
