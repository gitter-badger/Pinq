<?php

namespace Pinq\Tests\Integration\Providers\DSL;

use Pinq\Expressions as O;
use Pinq\Providers\DSL\Compilation\Parameters\ParameterHasher;
use Pinq\Queries\Functions;
use Pinq\Tests\PinqTestCase;

function userDefinedFunction(array &$gg = [1, 2, 3], $t = __LINE__, \stdClass $f = null)
{

}

class ParameterHasherTest extends PinqTestCase
{
    public function testValueTypeHasher()
    {
        $hasher = ParameterHasher::valueType();

        foreach ([1, 'erfse', 'DF$T$TWG$', 34343.34, null, true, false] as $value) {
            $this->assertSame($hasher->hash($value), $hasher->hash($value));
        }

        $this->assertNotSame($hasher->hash(3), $hasher->hash(5));
        $this->assertNotSame($hasher->hash('3'), $hasher->hash(3));
        $this->assertNotSame($hasher->hash(null), $hasher->hash(0));
        $this->assertNotSame($hasher->hash(null), $hasher->hash(false));
        $this->assertNotSame($hasher->hash(true), $hasher->hash(false));
        $this->assertNotSame($hasher->hash('abcdefg1'), $hasher->hash('abcdefg2'));
    }

    public static function staticFunction($t)
    {
        return $t;
    }

    public function testFunctionSignatureHasher()
    {
        $hasher = ParameterHasher::functionSignature();

        foreach ([
                         'strlen',
                         function () { },
                         [$this, 'getName'],
                         [$this, 'testFunctionSignatureHasher'],
                         __NAMESPACE__ . '\\userDefinedFunction',
                         [__CLASS__, 'staticFunction']
                 ] as $function) {
            $this->assertSame($hasher->hash($function), $hasher->hash($function));
        }

        //Indistinguishable signatures:
        $this->assertSame($hasher->hash(function () { }), $hasher->hash(function () { }));
        $this->assertSame($hasher->hash(function (\stdClass $foo = null) { }), $hasher->hash(function (\stdClass $foo = null) { }));
        //Case insensitive functions:
        $this->assertSame($hasher->hash('StrLen'), $hasher->hash('strleN'));

        $this->assertNotSame($hasher->hash('strlen'), $hasher->hash('strpos'));
        $this->assertNotSame($hasher->hash('stripos'), $hasher->hash('strpos'));
        $this->assertNotSame($hasher->hash([__CLASS__, 'staticFunction']), $hasher->hash(__NAMESPACE__ . '\\userDefinedFunction'));
        $this->assertNotSame($hasher->hash([__CLASS__, 'staticFunction']), $hasher->hash(__NAMESPACE__ . '\\userDefinedFunction'));
        $this->assertNotSame($hasher->hash(function ($i) { }), $hasher->hash(function ($o) { }));
        $this->assertNotSame($hasher->hash(function ($i) { }), $hasher->hash(function & ($i) { }));
        $this->assertNotSame($hasher->hash(function ($i) { }), $hasher->hash(function (&$i) { }));
        $this->assertNotSame($hasher->hash(function ($i) { }), $hasher->hash(function ($i, $i) { }));
        $this->assertNotSame($hasher->hash(function ($i) { }), $hasher->hash(function ($i = null) { }));
        //Same signature but distinguishable location
        $this->assertNotSame(
                $hasher->hash(function ($i) { }),
                $hasher->hash(function ($i) { }));
    }
}
 