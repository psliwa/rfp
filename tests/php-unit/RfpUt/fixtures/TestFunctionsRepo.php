<?php
namespace RfpUt\fixtures;
 
class TestFunctionsRepo
{
    public static function makeConcatFunc($current)
    {
        return function($prev) use ($current) {
            return "${prev}${current}";
        };
    }
}
 