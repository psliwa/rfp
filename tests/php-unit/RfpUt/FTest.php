<?php
namespace RfpUt;
 
use Rfp\F;

class FTest extends \PHPUnit_Framework_TestCase
{
    public function testCompose()
    {
        $f = fixtures\TestFunctionsRepo::makeConcatFunc('f');
        $g = fixtures\TestFunctionsRepo::makeConcatFunc('g');
        $cTest = F::compose($f, $g);

        $res = $cTest("0");
        $this->assertEquals("0gf", $res);
    }

    public function testPipe()
    {
        $f = fixtures\TestFunctionsRepo::makeConcatFunc('f');
        $g = fixtures\TestFunctionsRepo::makeConcatFunc('g');
        $cTest = F::pipe($f, $g);

        $res = $cTest("0");
        $this->assertEquals("0fg", $res);
    }
}
 