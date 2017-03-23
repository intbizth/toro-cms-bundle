<?php

namespace Toro\Bundle\CmsBundle\Processor;

use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Toro\Bundle\CmsBundle\Model\FlaggedAwareInterface;
use Toro\Bundle\CmsBundle\Model\FlaggedInterface;
use Toro\Bundle\CmsBundle\Model\FlaggedTypeInterface;

class FlaggedProcessor implements FlaggedProcessorInterface
{
    /**
     * @var RepositoryInterface
     */
    private $flaggedAwareRepository;

    /**
     * @var RepositoryInterface
     */
    private $typeRepository;

    /**
     * @var RepositoryInterface
     */
    private $flaggedRepository;

    /**
     * @var FactoryInterface
     */
    private $flaggedFactory;

    public function __construct(
        RepositoryInterface $flaggedAwareRepository,
        RepositoryInterface $flaggedTypeRepository,
        RepositoryInterface $flaggedRepository,
        FactoryInterface $flaggedFactory
    )
    {
        $this->flaggedAwareRepository = $flaggedAwareRepository;
        $this->flaggedTypeRepository = $flaggedTypeRepository;
        $this->flaggedRepository = $flaggedRepository;
        $this->flaggedFactory = $flaggedFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function updater(FlaggedAwareInterface $flaggedAware, array $flggedTypeIds = null)
    {
        $flaggedAware->getFlaggeds()->clear();

        if (empty($flggedTypeIds)) {
            return;
        }

        /** @var FlaggedTypeInterface[] $flggedTypes */
        $flggedTypes = $this->flaggedTypeRepository->findBy(['id' => $flggedTypeIds]);

        /** @var FlaggedTypeInterface[] $olderFlaggeds */
        $olderFlaggeds = $flaggedAware->getFlaggeds()->toArray();

        foreach ($flggedTypes as $flaggedType) {
            /** @var FlaggedInterface $flagged */
            $flagged = $this->flaggedFactory->createNew();
            $flagged->setType($flaggedType);

            $flaggedAware->addFlagged($flagged);

            if ($flaggedType->isSingleActive()) {
                $flaggeds = $this->flaggedRepository->findBy(['type' => $flaggedType]);

                foreach ($flaggeds as $flagged) {
                    $this->flaggedRepository->remove($flagged);
                }

                continue;
            }
        }
    }
}
