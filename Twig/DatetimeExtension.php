<?php

namespace Toro\Bundle\CmsBundle\Twig;

use Carbon\Carbon;

class DatetimeExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('cms_time_humans', array($this, 'getTimeHumans')),
            new \Twig_SimpleFunction('cms_time_age', array($this, 'getTimeAge')),
        );
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return string
     */
    public function getTimeHumans(\DateTime $dateTime)
    {
        return Carbon::createFromTimestamp($dateTime->getTimestamp())->diffForHumans();
    }

    /**
     * @param \DateTime|null $dateTime
     *
     * @return int|string
     */
    public function getTimeAge(\DateTime $dateTime = null)
    {
        if (null === $dateTime) {
            return '-';
        }

        return Carbon::createFromTimestamp($dateTime->getTimestamp())->age;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'toro_cms_datetime';
    }
}
