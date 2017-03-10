<?php

namespace Toro\Bundle\CmsBundle\Model;

abstract class Analytic implements AnalyticInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $views;

    /**
     * @var int
     */
    protected $shares;

    /**
     * @var int
     */
    protected $sessions;

    /**
     * @var int
     */
    protected $pageviews;

    /**
     * @var int
     */
    protected $users;

    /**
     * @var float
     */
    protected $shareAvg;

    /**
     * @var float
     */
    protected $sharePercentage;

    /**
     * @var AnalyticAwareInterface
     */
    protected $analyticAware;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getViews()
    {
        return (int)$this->views;
    }

    /**
     * {@inheritdoc}
     */
    public function setViews($views)
    {
        $this->views = (int)$views;
    }

    /**
     * {@inheritdoc}
     */
    public function getShares()
    {
        return (int)$this->shares;
    }

    /**
     * {@inheritdoc}
     */
    public function setShares($shares)
    {
        $this->shares = (int)$shares;
    }

    /**
     * {@inheritdoc}
     */
    public function getSessions()
    {
        return (int)$this->sessions;
    }

    /**
     * {@inheritdoc}
     */
    public function setSessions($sessions)
    {
        $this->sessions = (int)$sessions;
    }

    /**
     * {@inheritdoc}
     */
    public function getPageviews()
    {
        return (int)$this->pageviews;
    }

    /**
     * {@inheritdoc}
     */
    public function setPageviews($pageviews)
    {
        $this->pageviews = (int)$pageviews;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsers()
    {
        return (int)$this->users;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsers($users)
    {
        $this->users = (int)$users;
    }

    /**
     * {@inheritdoc}
     */
    public function getShareAvg()
    {
        return (float)$this->shareAvg;
    }

    /**
     * {@inheritdoc}
     */
    public function setShareAvg($shareAvg)
    {
        $this->shareAvg = (float)$shareAvg;
    }

    /**
     * {@inheritdoc}
     */
    public function getSharePercentage()
    {
        return (float)$this->sharePercentage;
    }

    /**
     * {@inheritdoc}
     */
    public function setSharePercentage($sharePercentage)
    {
        $this->sharePercentage = (float)$sharePercentage;
    }

    /**
     * {@inheritdoc}
     */
    public function calculateShareAvg()
    {
        $this->shareAvg = $this->getShares() / $this->getViews();
    }

    /**
     * {@inheritdoc}
     */
    public function calculateSharePercentage()
    {
        $this->sharePercentage = ($this->getShares() * 100) / $this->getViews();
    }

    /**
     * {@inheritdoc}
     */
    public function calculate()
    {
        $this->calculateShareAvg();
        $this->calculateSharePercentage();
    }

    /**
     * {@inheritdoc}
     */
    public function getAnalyticAware()
    {
        return $this->analyticAware;
    }

    /**
     * {@inheritdoc}
     */
    public function setAnalyticAware(AnalyticAwareInterface $analyticAware = null)
    {
        $this->analyticAware = $analyticAware;
    }
}
