<?php

namespace Toro\Bundle\CmsBundle\Theme\Detection;

use SunCat\MobileDetectBundle\Helper\DeviceView;

final class DeviceDetection
{
    /**
     * @var DeviceView
     */
    private $deviceView;

    public function __construct(DeviceView $deviceView)
    {
        $this->deviceView = $deviceView;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        switch (true) {
            case $this->deviceView->isMobileView():
                return 'mobile';
            case $this->deviceView->isTabletView():
                return 'tablet';
            default:
                return null;
        }
    }
}
