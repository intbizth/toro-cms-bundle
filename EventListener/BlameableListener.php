<?php

namespace Toro\Bundle\CmsBundle\EventListener;

use Gedmo\Blameable\BlameableListener as BaseBlameableListener;

class BlameableListener extends BaseBlameableListener
{
    public function getFieldValue($meta, $field, $eventAdapter)
    {
        $user = parent::getFieldValue($meta, $field, $eventAdapter);

        if ($user && $meta->hasAssociation($field)) {
            $mapping = $meta->getAssociationMapping($field);

            if (!$user instanceof $mapping['targetEntity']) {
                return null;
            }
        }

        return $user;
    }
}
