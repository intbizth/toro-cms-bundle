<?php

namespace Toro\Bundle\CmsBundle\Locale\Handler;

use Toro\Bundle\CmsBundle\Exception\HandleException;
use Zend\Stdlib\PriorityQueue;

final class CompositeLocaleChangeHandler implements LocaleChangeHandlerInterface
{
    /**
     * @var PriorityQueue|LocaleChangeHandlerInterface[]
     */
    private $handlers;

    public function __construct()
    {
        $this->handlers = new PriorityQueue();
    }

    /**
     * @param LocaleChangeHandlerInterface $localeChangeHandler
     * @param int $priority
     */
    public function addHandler(LocaleChangeHandlerInterface $localeChangeHandler, $priority = 0)
    {
        $this->handlers->insert($localeChangeHandler, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function handle($code)
    {
        if ($this->handlers->isEmpty()) {
            throw new HandleException(self::class, 'There are no handlers defined.');
        }

        foreach ($this->handlers as $handler) {
            $handler->handle($code);
        }
    }
}
