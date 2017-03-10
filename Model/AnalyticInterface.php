<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface AnalyticInterface extends ResourceInterface
{
    /**
     * @return int
     */
    public function getViews();

    /**
     * @param int $views
     */
    public function setViews($views);

    /**
     * @return int
     */
    public function getShares();

    /**
     * @param int $shares
     */
    public function setShares($shares);

    /**
     * @return int
     */
    public function getSessions();

    /**
     * @param int $sessions
     */
    public function setSessions($sessions);

    /**
     * @return int
     */
    public function getPageviews();

    /**
     * @param int $pageviews
     */
    public function setPageviews($pageviews);

    /**
     * @return int
     */
    public function getUsers();

    /**
     * @param int $users
     */
    public function setUsers($users);

    /**
     * @return float
     */
    public function getShareAvg();

    /**
     * @param float $shareAvg
     */
    public function setShareAvg($shareAvg);

    /**
     * @return float
     */
    public function getSharePercentage();

    /**
     * @param float $sharePercentage
     */
    public function setSharePercentage($sharePercentage);

    /**
     * Calculate avg
     */
    public function calculateShareAvg();

    /**
     * Calculate %
     */
    public function calculateSharePercentage();

    /**
     * Calculate avg and %
     */
    public function calculate();

    /**
     * @return AnalyticAwareInterface
     */
    public function getAnalyticAware();

    /**
     * @param AnalyticAwareInterface $analyticAware
     */
    public function setAnalyticAware(AnalyticAwareInterface $analyticAware = null);
}
