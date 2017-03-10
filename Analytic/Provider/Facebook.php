<?php

namespace Toro\Bundle\CmsBundle\Analytic\Provider;

use SocialShare\Provider\Facebook as BaseFacebook;

class Facebook extends BaseFacebook
{
    /**
     * {@inheritdoc}
     */
    public function getShares($url)
    {
        $data = json_decode(file_get_contents(sprintf(self::API_URL, urlencode($url))));

        if (isset($data->share)) {
            return intval($data->share->share_count);
        }

        return 0;
    }
}
