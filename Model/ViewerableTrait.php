<?php

namespace Toro\Bundle\CmsBundle\Model;

trait ViewerableTrait
{
    /**
     * @var int
     */
    protected $viewers = 0;

    /**
     * {@inheritdoc}
     */
    public function getViewers()
    {
        return $this->viewers;
    }

    /**
     * {@inheritdoc}
     */
    public function setViewers($viewers)
    {
        $this->viewers = $viewers;
    }

    /**
     * {@inheritdoc}
     */
    public function increaseViewer()
    {
        $this->viewers++;
    }
}
