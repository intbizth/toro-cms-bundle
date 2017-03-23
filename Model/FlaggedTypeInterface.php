<?php

namespace Toro\Bundle\CmsBundle\Model;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface FlaggedTypeInterface extends CodeAwareInterface, ResourceInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getCode();

    /**
     * @param string $code
     */
    public function setCode($code);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return array
     */
    public function getConfig();

    /**
     * @param array $config
     */
    public function setConfig(array $config = array());

    /**
     * @return boolean
     */
    public function isEnabled();

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled);

    /**
     * @return boolean
     */
    public function isSingleActive();

    /**
     * @param boolean $singleActive
     */
    public function setSingleActive($singleActive);

    /**
     * @return Collection|FlaggedInterface[]
     */
    public function getItems();

    /**
     * @param Collection|FlaggedInterface[] $items
     */
    public function setItems(Collection $items);

    /**
     * @param FlaggedInterface $item
     *
     * @return bool
     */
    public function hasItem(FlaggedInterface $item);

    /**
     * @param FlaggedInterface $item
     */
    public function addItem(FlaggedInterface $item);

    /**
     * @param FlaggedInterface $item
     */
    public function removeItem(FlaggedInterface $item);
}
