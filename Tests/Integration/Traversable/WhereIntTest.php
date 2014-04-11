<?php

namespace Pinq\Tests\Integration\Traversable;

class WhereIntTest extends TraversableTest
{
    /**
     * @dataProvider Everything
     */
    public function testThatWhereInWithSelfReturnsAllValues(\Pinq\ITraversable $Traversable, array $Data)
    {
        $Values = $Traversable->WhereIn($Traversable);
        
        $this->AssertMatches($Values, $Data);
    }
    
    /**
     * @dataProvider Everything
     */
    public function testThatWhereInWithEmptyReturnsEmpty(\Pinq\ITraversable $Traversable, array $Data)
    {
        $Values = $Traversable->WhereIn(new \Pinq\Traversable());
        
        $this->AssertMatches($Values, []);
    }
    
    
    /**
     * @dataProvider OneToTen
     */
    public function testThatWhereInWithDuplicateValuesPreservesTheOriginalKeys(\Pinq\ITraversable $Traversable, array $Data)
    {
        $OtherData = ['test' => 1, 'anotherkey' => 3, 1000 => 5];
        $ValuesWithSomeMatchingValues = new \Pinq\Traversable($OtherData);
        
        $Values = $Traversable->WhereIn($ValuesWithSomeMatchingValues);
        
        $this->AssertMatches($Values, array_intersect($Data, $OtherData));
    }
    
    /**
     * @dataProvider OneToTen
     */
    public function testThatWhereInUsesStrictEquality(\Pinq\ITraversable $Traversable, array $Data)
    {
        $OtherData = ['1', '2', '3', '4', '5'];
        $ValuesWithEquivalentStringValues = new \Pinq\Traversable($OtherData);
        $Insection = $Traversable->Intersect($ValuesWithEquivalentStringValues);
        
        $this->AssertMatches($Insection, []);
    }
}