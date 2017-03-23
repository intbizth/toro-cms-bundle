<?php

namespace Toro\Bundle\CmsBundle\Model;

interface FlaggedAwareInterface
{
    /**
     * @return FlaggedTypeInterface[]
     */
    public function getFlaggedTypes();

    /**
     * @return Collection|FlaggedInterface[]
     */
    public function getFlaggeds();

    /**
     * @param FlaggedInterface $flagged
     */
    public function addFlagged(FlaggedInterface $flagged);

    /**
     * @param FlaggedInterface $flagged
     */
    public function removeFlagged(FlaggedInterface $flagged);

    /**
     * @param FlaggedInterface $flagged
     *
     * @return boolean
     */
    public function hasFlagged(FlaggedInterface $flagged);
}
