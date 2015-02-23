<?php
namespace RfpUt;
 
class AllTests extends \PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        $suite = new self();
        $suite->addTestSuite(new FTest());
        return $suite;
    }
}
 