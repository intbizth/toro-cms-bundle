<?php

namespace Toro\Bundle\CmsBundle\Grid\Filter;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

final class MultiStringFilter implements FilterInterface
{
    const NAME = 'multi_string';

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
        $value = trim(preg_replace('/,/', ' ', $value));

        if (!in_array($type, [self::TYPE_NOT_EMPTY, self::TYPE_EMPTY], true) && '' === $value) {
            return;
        }

        $value = trim(preg_replace('/\s+/', ' ', $value));
        $value = explode(' ', $value);

        if (1 === count($fields)) {
            $exprs = [];

            foreach ((array) $value as $val) {
                $exprs[] = $this->getExpression($expressionBuilder, $type, current($fields), $val);
            }

            $dataSource->restrict($expressionBuilder->orX(...$exprs));

            return;
        }

        $expressions = [];

        foreach ($fields as $field) {
            foreach ((array) $value as $val) {
                $expressions[] = $this->getExpression($expressionBuilder, $type, $field, $val);
            }
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
