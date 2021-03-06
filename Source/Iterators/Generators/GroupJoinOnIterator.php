<?php

namespace Pinq\Iterators\Generators;

use Pinq\Iterators\Common;

/**
 * Implementation of the join iterator using generators.
 *
 * @author Elliot Levin <elliotlevin@hotmail.com>
 */
class GroupJoinOnIterator extends GroupJoinIterator
{
    use Common\JoinOnIterator;

    public function __construct(
            IGenerator $outerIterator,
            IGenerator $innerIterator,
            callable $traversableFactory,
            callable $filter
    ) {
        parent::__construct($outerIterator, $innerIterator, $traversableFactory);
        self::__constructJoinOnIterator($filter);
    }

    protected function innerGenerator($outerKey, $outerValue)
    {
        $innerValues = new OrderedMap($this->innerIterator);

        return new ArrayIterator([
                0 => $this->constructInnerGroup(
                                $this->defaultIterator(
                                        new FilterIterator(
                                                $innerValues,
                                                $this->innerElementFilter($outerKey, $outerValue))
                                )
                        )
        ]);
    }
}
