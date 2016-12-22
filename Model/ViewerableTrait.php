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

    /**
     * {@inheritdoc}
     */
    public function isViewerEnabled()
    {
        if (in_array(PageInterface::class, class_implements(get_called_class()))) {
            return !$this->isPartial();
        }

        return true;
    }
}
