<?php

namespace Toro\Bundle\CmsBundle\Twig;

class TypeTestExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getTests()
    {
        return array('type_of' => new \Twig_SimpleTest($this, 'getOfType'));
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array('get_type' => new \Twig_Filter_Method($this, 'getType'));
    }

    /**
     * @param $var
     * @param null $typeTest
     * @param null $className
     *
     * @return bool
     */
    public function getOfType($var, $typeTest = null, $className = null)
    {
        switch ($typeTest) {
            default:
                return false;
                break;

            case 'array':
                return is_array($var);
                break;

            case 'bool':
                return is_bool($var);
                break;

            case 'class':
                return is_object($var) === true && get_class($var) === $className;
                break;

            case 'float':
                return is_float($var);
                break;

            case 'int':
                return is_int($var);
                break;

            case 'numeric':
                return is_numeric($var);
                break;

            case 'object':
                return is_object($var);
                break;

            case 'scalar':
                return is_scalar($var);
                break;

            case 'string':
                return is_string($var);
                break;
        }
    }

    /**
     * @param $var
     *
     * @return string
     */
    public function getType($var)
    {
        return gettype($var);
    }
}
