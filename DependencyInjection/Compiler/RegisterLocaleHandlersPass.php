<?php

namespace Toro\Bundle\CmsBundle\DependencyInjection\Compiler;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\PrioritizedCompositeServicePass;

final class RegisterLocaleHandlersPass extends PrioritizedCompositeServicePass
{
    public function __construct()
    {
        parent::__construct(
            'sylius.handler.locale_change',
            'sylius.handler.locale_change.composite',
            'sylius.locale.change_handler',
            'addHandler'
        );
    }
}
