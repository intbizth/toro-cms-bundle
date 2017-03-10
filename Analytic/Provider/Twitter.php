<?php

namespace Toro\Bundle\CmsBundle\Analytic\Provider;

use SocialShare\Provider\Twitter as BaseTwitter;

class Twitter extends BaseTwitter
{
    const API_URL = 'https://opensharecount.com/count.json?url=%s';

    /**
     * {@inheritdoc}
     */
    public function getShares($url)
    {
        $data = json_decode(file_get_contents(sprintf(self::API_URL, urlencode($url))));

        return intval($data->count);
    }
}
