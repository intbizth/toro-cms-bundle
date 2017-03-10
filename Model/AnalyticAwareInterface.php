<?php

namespace Toro\Bundle\CmsBundle\Model;

interface AnalyticAwareInterface
{
    /**
     * @return AnalyticInterface
     */
    public function getAnalytic();

    /**
     * @param AnalyticInterface $analytic
     */
    public function setAnalytic(AnalyticInterface $analytic = null);
}
