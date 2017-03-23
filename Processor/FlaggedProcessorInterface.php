<?php

namespace Toro\Bundle\CmsBundle\Processor;

use Toro\Bundle\CmsBundle\Model\FlaggedAwareInterface;

interface FlaggedProcessorInterface
{
    /**
     * @param FlaggedAwareInterface $flaggedAware
     * @param array|null $flggedTypeIds
     */
    public function updater(FlaggedAwareInterface $flaggedAware, array $flggedTypeIds = null);
}
