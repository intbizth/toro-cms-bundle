<?php

namespace Toro\Bundle\CmsBundle\Form;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Toro\Bundle\MediaBundle\Form\ImageCollectionConfigureResolverInterface;

class ChannelBasedImageCollectionConfigureResolver implements ImageCollectionConfigureResolverInterface
{
    /**
     * @var ImageCollectionConfigureResolverInterface
     */
    private $baseResolver;

    /**
     * @var ChannelInterface
     */
    private $channelContext;

    public function __construct(ImageCollectionConfigureResolverInterface $baseResolver, ChannelContextInterface $channelContext)
    {
        $this->baseResolver = $baseResolver;
        $this->channelContext = $channelContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigs()
    {
        $defaultSettings = (array) $this->baseResolver->getConfigs();
        $settings = (array) $this->channelContext->getChannel()->getSettings();

        if (array_key_exists('image_collection', $settings)) {
            $defaultSettings = array_replace_recursive($defaultSettings, $settings['image_collection']);
        }

        foreach ($defaultSettings as &$filters) {
            if (array_key_exists('filters', $filters)) {
                // TODO: better sorting
                ksort($filters['filters']);
            }
        }

        return $defaultSettings;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig($name)
    {
        $configs = $this->getConfigs();

        if (array_key_exists($name, $configs)) {
            return $configs[$name];
        }

        return;
    }
}
