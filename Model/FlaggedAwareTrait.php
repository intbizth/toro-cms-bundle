<?php

namespace Toro\Bundle\CmsBundle\Model;

trait FlaggedAwareTrait
{
    /**
     * @var Collection|FlaggedInterface[]
     */
    protected $flaggeds;

    /**
     * {@inheritdoc}
     */
    public function getFlaggeds()
    {
        return $this->flaggeds;
    }

    /**
     * {@inheritdoc}
     */
    public function getFlaggedTypes()
    {
        return array_map(function (FlaggedInterface $flagged) {
            return $flagged->getType();
        }, $this->flaggeds->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function addFlagged(FlaggedInterface $flagged)
    {
        if (!$this->hasFlagged($flagged)) {
            $flagged->setPost($this);
            $this->flaggeds->add($flagged);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeFlagged(FlaggedInterface $flagged)
    {
        if ($this->hasFlagged($flagged)) {
            $flagged->setPost(null);
            $this->flaggeds->removeElement($flagged);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasFlagged(FlaggedInterface $flagged)
    {
        return $this->flaggeds->contains($flagged);
    }
}
