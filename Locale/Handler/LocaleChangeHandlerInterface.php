<?php

namespace Toro\Bundle\CmsBundle\Locale\Handler;

use Toro\Bundle\CmsBundle\Exception\HandleException;

interface LocaleChangeHandlerInterface
{
    /**
     * @param string $code
     *
     * @throws HandleException
     */
    public function handle($code);
}
