<?php

namespace Toro\Bundle\CmsBundle\Exception;

class HandleException extends \RuntimeException
{
    /**
     * @param string $handlerName
     * @param string $message
     * @param \Exception|null $previousException
     */
    public function __construct($handlerName, $message, \Exception $previousException = null)
    {
        parent::__construct(
            sprintf(
                '%s was unable to handle this request. %s',
                $handlerName,
                $message
            ),
            0,
            $previousException
        );
    }
}
