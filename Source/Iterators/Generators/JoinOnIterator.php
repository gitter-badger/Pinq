<?php

namespace Pinq\Iterators\Generators;

use Pinq\Iterators\Common;

/**
 * Implementation of the join iterator using generators.
 *
 * @author Elliot Levin <elliot@aanet.com.au>
 */
class JoinOnIterator extends JoinIterator
{
    use Common\JoinOnIterator;
    
    public function __construct(IGenerator $outerIterator, IGenerator $innerIterator, callable $filter)
    {
        parent::__construct($outerIterator, $innerIterator);
        self::__constructJoinOnIterator($filter);
    }
    
    protected function innerGenerator($outerKey, $outerValue)
    {
        $innerValues = new OrderedMap($this->innerIterator);
        
        return $this->defaultIterator(new FilterIterator(
                $innerValues,
                $this->innerElementFilter($outerKey, $outerValue)));
    }
}
