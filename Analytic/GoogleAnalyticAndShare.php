<?php

namespace Toro\Bundle\CmsBundle\Analytic;

use Doctrine\Common\Cache\Cache;
use SocialShare\SocialShare;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Toro\Bundle\CmsBundle\Model\AnalyticAwareInterface;
use Toro\Bundle\CmsBundle\Model\AnalyticInterface;
use Toro\Bundle\CmsBundle\Model\ViewerableInterface;

class GoogleAnalyticAndShare extends SocialShare
{
    const SHARE_COUNT_API = 'https://free.sharedcount.com/?url=%s&apikey=%s';

    /**
     * @var string
     */
    private $shareCountApiKey;

    /**
     * @var string
     */
    private $googleConfigFile;

    /**
     * @var string
     */
    private $googleAnalyticViewId;

    public function __construct(Cache $cache, $googleConfigFile = null, $googleAnalyticViewId = null, $shareCountApiKey = null)
    {
        parent::__construct($cache);

        $this->shareCountApiKey = $shareCountApiKey;
        $this->googleConfigFile = $googleConfigFile;
        $this->googleAnalyticViewId = $googleAnalyticViewId;
    }

    /**
     * @param $url
     *
     * @return array
     *  +"StumbleUpon": 0
        +"Reddit": 0
        +"Facebook": {
            +"total_count": 2353
            +"comment_count": 0
            +"share_count": 2353
        }
        +"Delicious": 0
        +"GooglePlusOne": 0
        +"Buzz": 0
        +"Twitter": 0
        +"Diggs": 0
        +"Pinterest": 0
        +"LinkedIn": 0
     */
    public function getSharesCount($url)
    {
        return json_decode(file_get_contents(sprintf(self::SHARE_COUNT_API, urlencode($url), $this->shareCountApiKey)), true);
    }

    /**
     * @param $url
     *
     * @return int
     */
    public function getSharesCountTotal($url)
    {
        $data = $this->getSharesCount($url);
        $facebook = $data['Facebook'];
        unset($data['Facebook']);

        return intval(array_sum(array_values($data)) + $facebook['total_count']);
    }

    /**
     * @return array
     */
    public function getGoogleMetrics()
    {
        return ['sessions', 'pageviews', 'users'];
    }

    public function getGooglePageViews($path = null)
    {
        if (!$this->googleConfigFile) {
            return;
        }

        $client = new \Google_Client();
        $client->setAuthConfigFile($this->googleConfigFile);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);

        return $this->createReport($client, $this->googleAnalyticViewId, $this->getGoogleMetrics(), $path);
    }

    private function createReport(\Google_Client $client, $viewId, array $metrics, $path = null)
    {
        $analytics = new \Google_Service_AnalyticsReporting($client);
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate("2005-01-01");
        $dateRange->setEndDate("today");

        $objectMetrics = [];

        foreach ($metrics as $metric) {
            $objectMetric = new \Google_Service_AnalyticsReporting_Metric();
            $objectMetric->setExpression("ga:$metric");
            $objectMetric->setAlias("$metric");

            $objectMetrics[] = $objectMetric;
        }

        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setMetrics($objectMetrics);
        $request->setDateRanges($dateRange);
        $request->setViewId($viewId);

        if ($path) {
            $request->setFiltersExpression('ga:pagePath==' . $path);
        }

        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests([$request]);


        /** @var \Google_Service_AnalyticsReporting_Report[] $data */
        if (!$data = $analytics->reports->batchGet($body)) {
            return;
        }

        /** @var \Google_Service_AnalyticsReporting_ReportData $data */
        $data = $data[0]->getData();
        $totals = $data->getTotals()[0]['values'];
        $results = [];

        foreach ($totals as $i => $value) {
            $results[$metrics[$i]] = $value;
        }

        return $results;
    }

    public function analize($path, $domain, AnalyticAwareInterface $analyticAware, AnalyticInterface $analytic)
    {
        if ($shares = $this->getSharesTotal($domain . $path)) {
            $analytic->setShares($shares);
        }

        if ($analytics = $this->getGooglePageViews($path)) {
            $accessor = PropertyAccess::createPropertyAccessorBuilder()
                ->disableExceptionOnInvalidIndex()
                ->disableMagicCall()
                ->getPropertyAccessor()
            ;

            foreach ($analytics as $key => $value) {
                if ($accessor->isWritable($analytic, $key)) {
                    $accessor->setValue($analytic, $key, $value);
                }
            }
        }

        $analytic->setViews($analytic->getUsers());

        if ($analyticAware instanceof ViewerableInterface && $analyticAware->getViewers()) {
            $analytic->setViews($analyticAware->getViewers());
        }

        $analytic->calculate();
    }
}
