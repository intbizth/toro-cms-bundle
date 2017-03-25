<?php

namespace Toro\Bundle\CmsBundle\Grid\Filter;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

class FlaggedTypeFilter implements FilterInterface
{
    const NAME = 'flagged';

    /**
     * {@inheritdoc}
     */
    public function apply(DataSourceInterface $dataSource, $name, $data, array $options)
    {
        $expr = $dataSource->getExpressionBuilder();
        $fields = array_key_exists('fields', $options) ? $options['fields'] : [$name];
        $field = current($fields);

        if ('equal' === $data['type']) {
            $dataSource->restrict($expr->equals($field, $data['value']));
        }

        //
        if ('not_equal' === $data['type']) {
            $dataSource->restrict($expr->orX(
                $expr->notEquals($field, $data['value']),
                $expr->isNull($field)
            ));
        }
    }
}
