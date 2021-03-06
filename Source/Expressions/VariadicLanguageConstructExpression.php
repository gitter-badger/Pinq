<?php

namespace Pinq\Expressions;

/**
 * Base class for a variadic language construct.
 *
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
abstract class VariadicLanguageConstructExpression extends Expression
{
    /**
     * @var Expression[]
     */
    private $values;

    public function __construct(array $values)
    {
        if (count($values) === 0) {
            throw new \Pinq\PinqException(
                    'Invalid amount of value expressions for %s: must be greater than 0',
                    __CLASS__);
        }

        $this->values = self::verifyAll($values);
    }

    /**
     * @return Expression[]
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param Expression[] $values
     *
     * @return static
     */
    public function update(array $values)
    {
        if ($this->values === $values) {
            return $this;
        }

        return $this->updateValues($values);
    }

    abstract protected function updateValues(array $values);

    final protected function compileParameters(&$code)
    {
        $code .= implode(',', self::compileAll($this->values));
    }

    final public function serialize()
    {
        return serialize([$this->values]);
    }

    final public function unserialize($serialized)
    {
        list($this->values) = unserialize($serialized);
    }

    final public function __clone()
    {
        $this->values = self::cloneAll($this->values);
    }
}
