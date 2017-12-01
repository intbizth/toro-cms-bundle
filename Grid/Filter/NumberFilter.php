<?php

namespace Toro\Bundle\CmsBundle\Grid\Filter;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

final class NumberFilter implements FilterInterface
{
    const NAME = 'number';

    const TYPE_EQUAL = 'equal';
    const TYPE_NOT_EQUAL = 'not_equal';
    const TYPE_EMPTY = 'empty';
    const TYPE_NOT_EMPTY = 'not_empty';
    const TYPE_CONTAINS = 'contains';
    const TYPE_NOT_CONTAINS = 'not_contains';
    const TYPE_STARTS_WITH = 'starts_with';
    const TYPE_ENDS_WITH = 'ends_with';
    const TYPE_IN = 'in';
    const TYPE_NOT_IN = 'not_in';
    const TYPE_GREATER_THAN = 'greater_than';
    const TYPE_GREATER_THAN_OR_EQUAL = 'greater_than_or_equal';
    const TYPE_LESS_THAN = 'less_than';
    const TYPE_LESS_THAN_OR_EQUAL = 'less_than_or_equal';

    /**
     * {@inheritdoc}
     */
    public function apply(DataSourceInterface $dataSource, string $name, $data, array $options): void
    {
        $expressionBuilder = $dataSource->getExpressionBuilder();

        if (is_array($data) && !isset($data['type'])) {
            $data['type'] = isset($options['type']) ? $options['type'] : self::TYPE_CONTAINS;
        }

        if (!is_array($data)) {
            $data = ['type' => self::TYPE_CONTAINS, 'value' => $data];
        }

        $fields = array_key_exists('fields', $options) ? $options['fields'] : [$name];

        $type = $data['type'];
        $value = array_key_exists('value', $data) ? $data['value'] : null;
        $value = is_array($value) ? implode(',', $value) : $value;

        if (!in_array($type, [self::TYPE_NOT_EMPTY, self::TYPE_EMPTY], true) && '' === trim($value)) {
            return;
        }

        if (1 === count($fields)) {
            $dataSource->restrict($this->getExpression($expressionBuilder, $type, current($fields), $value));

            return;
        }

        $expressions = [];
        foreach ($fields as $field) {
            $expressions[] = $this->getExpression($expressionBuilder, $type, $field, $value);
        }

        if (self::TYPE_NOT_EQUAL === $type) {
            $dataSource->restrict($expressionBuilder->andX(...$expressions));

            return;
        }

        $dataSource->restrict($expressionBuilder->orX(...$expressions));
    }

    /**
     * @param ExpressionBuilderInterface $expressionBuilder
     * @param string $type
     * @param string $field
     * @param mixed $value
     *
     * @return ExpressionBuilderInterface
     */
    private function getExpression(ExpressionBuilderInterface $expressionBuilder, $type, $field, $value)
    {
        switch ($type) {
            case self::TYPE_GREATER_THAN:
                return $expressionBuilder->greaterThan($field, $value);
            case self::TYPE_GREATER_THAN_OR_EQUAL:
                return $expressionBuilder->greaterThanOrEqual($field, $value);
            case self::TYPE_LESS_THAN:
                return $expressionBuilder->lessThan($field, $value);
            case self::TYPE_LESS_THAN_OR_EQUAL:
                return $expressionBuilder->lessThanOrEqual($field, $value);
            case self::TYPE_EQUAL:
                return $expressionBuilder->equals($field, $value);
            case self::TYPE_NOT_EQUAL:
                return $expressionBuilder->notEquals($field, $value);
            case self::TYPE_EMPTY:
                return $expressionBuilder->isNull($field);
            case self::TYPE_NOT_EMPTY:
                return $expressionBuilder->isNotNull($field);
            case self::TYPE_CONTAINS:
                return $expressionBuilder->like($field, '%'.$value.'%');
            case self::TYPE_NOT_CONTAINS:
                return $expressionBuilder->notLike($field, '%'.$value.'%');
            case self::TYPE_STARTS_WITH:
                return $expressionBuilder->like($field, $value.'%');
            case self::TYPE_ENDS_WITH:
                return $expressionBuilder->like($field, '%'.$value);
            case self::TYPE_IN:
                return $expressionBuilder->in($field, array_map('trim', explode(',', $value)));
            case self::TYPE_NOT_IN:
                return $expressionBuilder->notIn($field, array_map('trim', explode(',', $value)));
            default:
                throw new \InvalidArgumentException(sprintf('Could not get an expression for type "%s"!', $type));
        }
    }
}
