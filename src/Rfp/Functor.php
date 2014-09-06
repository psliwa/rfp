<?php


namespace Rfp;


class Functor
{
    private $function;

    public function __construct($function)
    {
        $this->function = $function;
    }

    public function __invoke()
    {
        return call_user_func_array($this->function, func_get_args());
    }

    public function apply()
    {
        return call_user_func_array($this->function, func_get_args());
    }
} 