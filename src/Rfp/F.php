<?php

namespace Rfp;

final class F
{
    public static function compose($f = null, $g = null){
        return new Functor(call_user_func_array('igorw\\compose', func_get_args()));
    }

    public static function pipe($f = null, $g = null){
        return new Functor(call_user_func_array('igorw\\pipeline', func_get_args()));
    }

    public static function curry($func, $arity, $args = array()) {
        if(count($args) >= $arity) {
            return call_user_func_array($func, $args);
        } else {
            return new Functor(function() use($func, $arity, $args){
                return call_user_func_array(array('Rfp\\F', 'curry'), array($func, $arity, array_merge($args, func_get_args())));
            });
        }
    }

    private static function _prop($name, $object) {
        if(is_object($object)) {
            $getterPrefixes = array('get', 'is', 'has', '');

            foreach($getterPrefixes as $prefix) {
                $getter = $prefix.$name;

                if(is_callable(array($object, $name))) {
                    return $object->$getter();
                }
            }

            if(isset($object->$name)) {
                return $object->$name;
            }
        } else if(is_array($object) || $object instanceof \ArrayAccess) {
            return isset($object[$name]) ? $object[$name] : null;
        }

        return null;
    }

    public static function prop($name = null, $object = null) {
        return self::curry(array('Rfp\\F', '_prop'), 2, func_get_args());
    }

    private static function _eq($value1, $value2) {
        return $value1 == $value2;
    }

    public static function eq($value1 = null, $value2 = null) {
        return self::curry(array('Rfp\\F', '_eq'), 2, func_get_args());
    }

    private static function _unary($func) {
        return function($arg) use($func) {
            return call_user_func($func, $arg);
        };
    }

    public static function unary($func = null) {
        return self::curry(array('Rfp\\F', '_unary'), 1, func_get_args());
    }

    private static function _apply($func, array $args) {
        return call_user_func_array($func, $args);
    }

    public static function apply($func = null, array $args = null) {
        return self::curry(array('Rfp\\F', '_apply'), 2, func_get_args());
    }

    private static function _add($a, $b) {
        return $a + $b;
    }

    public static function add($a = null, $b = null) {
        return self::curry(array('Rfp\\F', '_add'), 2, func_get_args());
    }

    private static function _multiply($a, $b) {
        return $a * $b;
    }

    public static function multiply($a = null, $b = null) {
        return self::curry(array('Rfp\\F', '_multiply'), 2, func_get_args());
    }

    private static function _converge($then, $func1, $func2)
    {
        return new Functor(function() use($then, $func1, $func2){
            $args = func_get_args();
            return $then(call_user_func_array($func1, $args), call_user_func_array($func2, $args));
        });
    }

    public static function converge()
    {
        return self::curry(array('Rfp\\F', '_converge'), 3, func_get_args());
    }

    private static function _zip($func, $collection) {
        $args = func_get_args();
        $func = array_shift($args);
        $args[] = $func;

        return call_user_func_array('Functional\\zip', $args);
    }

    public static function zip($func = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_zip'), 2, func_get_args());
    }

    private static function _average($collection) {
        return call_user_func('Functional\\average', $collection);
    }

    public static function average($collection = null) {
        return self::curry(array('Rfp\\F', '_average'), 1, func_get_args());
    }

    private static function _contains($value, $collection) {
        return call_user_func('Functional\\contains', $collection, $value);
    }

    public static function contains($value = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_contains'), 2, func_get_args());
    }

    private static function _difference($collection) {
        return call_user_func('Functional\\difference', $collection);
    }

    public static function difference($collection = null) {
        return self::curry(array('Rfp\\F', '_difference'), 1, func_get_args());
    }

    private static function _drop_first($callback, $collection) {
        return call_user_func('Functional\\drop_first', $collection, $callback);
    }

    public static function drop_first($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_drop_first'), 2, func_get_args());
    }

    private static function _drop_last($callback, $collection) {
        return call_user_func('Functional\\drop_last', $collection, $callback);
    }

    public static function drop_last($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_drop_last'), 2, func_get_args());
    }

    private static function _each($callback, $collection) {
        return call_user_func('Functional\\each', $collection, $callback);
    }

    public static function each($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_each'), 2, func_get_args());
    }

    private static function _every($callback, $collection) {
        return call_user_func('Functional\\every', $collection, $callback);
    }

    public static function every($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_every'), 2, func_get_args());
    }

    private static function _false($collection) {
        return call_user_func('Functional\\false', $collection);
    }

    public static function false($collection = null) {
        return self::curry(array('Rfp\\F', '_false'), 1, func_get_args());
    }

    private static function _falsy($collection) {
        return call_user_func('Functional\\falsy', $collection);
    }

    public static function falsy($collection = null) {
        return self::curry(array('Rfp\\F', '_falsy'), 1, func_get_args());
    }

    private static function _filter($callback, $collection) {
        return call_user_func('Functional\\filter', $collection, $callback);
    }

    public static function filter($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_filter'), 2, func_get_args());
    }

    private static function _first($collection) {
        return call_user_func('Functional\\first', $collection);
    }

    public static function first($collection = null) {
        return self::curry(array('Rfp\\F', '_first'), 1, func_get_args());
    }

    private static function _first_index_of($value, $collection) {
        return call_user_func('Functional\\first_index_of', $collection, $value);
    }

    public static function first_index_of($value = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_first_index_of'), 2, func_get_args());
    }

    private static function _flatten($collection) {
        return call_user_func('Functional\\flatten', $collection);
    }

    public static function flatten($collection = null) {
        return self::curry(array('Rfp\\F', '_flatten'), 1, func_get_args());
    }

    private static function _group($callback, $collection) {
        return call_user_func('Functional\\group', $collection, $callback);
    }

    public static function group($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_group'), 2, func_get_args());
    }

    private static function _head($collection) {
        return call_user_func('Functional\\head', $collection);
    }

    public static function head($collection = null) {
        return self::curry(array('Rfp\\F', '_head'), 1, func_get_args());
    }

    private static function _invoke($methodName, $collection) {
        return call_user_func('Functional\\invoke', $collection, $methodName);
    }

    public static function invoke($methodName = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_invoke'), 2, func_get_args());
    }

    private static function _invoke_first($methodName, $collection) {
        return call_user_func('Functional\\invoke_first', $collection, $methodName);
    }

    public static function invoke_first($methodName = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_invoke_first'), 2, func_get_args());
    }

    private static function _invoke_last($methodName, $collection) {
        return call_user_func('Functional\\invoke_last', $collection, $methodName);
    }

    public static function invoke_last($methodName = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_invoke_last'), 2, func_get_args());
    }

    private static function _last($collection) {
        return call_user_func('Functional\\last', $collection);
    }

    public static function last($collection = null) {
        return self::curry(array('Rfp\\F', '_last'), 1, func_get_args());
    }

    private static function _last_index_of($value, $collection) {
        return call_user_func('Functional\\last_index_of', $collection, $value);
    }

    public static function last_index_of($value = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_last_index_of'), 2, func_get_args());
    }

    private static function _map($callback, $collection) {
        return call_user_func('Functional\\map', $collection, $callback);
    }

    public static function map($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_map'), 2, func_get_args());
    }

    private static function _maximum($collection) {
        return call_user_func('Functional\\maximum', $collection);
    }

    public static function maximum($collection = null) {
        return self::curry(array('Rfp\\F', '_maximum'), 1, func_get_args());
    }

    private static function _minimum($collection) {
        return call_user_func('Functional\\minimum', $collection);
    }

    public static function minimum($collection = null) {
        return self::curry(array('Rfp\\F', '_minimum'), 1, func_get_args());
    }

    private static function _none($callback, $collection) {
        return call_user_func('Functional\\none', $collection, $callback);
    }

    public static function none($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_none'), 2, func_get_args());
    }

    private static function _partition($callback, $collection) {
        return call_user_func('Functional\\partition', $collection, $callback);
    }

    public static function partition($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_partition'), 2, func_get_args());
    }

    private static function _pluck($propertyName, $collection) {
        return call_user_func('Functional\\pluck', $collection, $propertyName);
    }

    public static function pluck($propertyName = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_pluck'), 2, func_get_args());
    }

    private static function _product($collection) {
        return call_user_func('Functional\\product', $collection);
    }

    public static function product($collection = null) {
        return self::curry(array('Rfp\\F', '_product'), 1, func_get_args());
    }

    private static function _ratio($collection) {
        return call_user_func('Functional\\ratio', $collection);
    }

    public static function ratio($collection = null) {
        return self::curry(array('Rfp\\F', '_ratio'), 1, func_get_args());
    }

    private static function _reduce_left($callback, $collection) {
        return call_user_func('Functional\\reduce_left', $collection, $callback);
    }

    public static function reduce_left($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_reduce_left'), 2, func_get_args());
    }

    private static function _reduce_right($callback, $collection) {
        return call_user_func('Functional\\reduce_right', $collection, $callback);
    }

    public static function reduce_right($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_reduce_right'), 2, func_get_args());
    }

    private static function _reject($callback, $collection) {
        return call_user_func('Functional\\reject', $collection, $callback);
    }

    public static function reject($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_reject'), 2, func_get_args());
    }

    private static function _select($callback, $collection) {
        return call_user_func('Functional\\select', $collection, $callback);
    }

    public static function select($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_select'), 2, func_get_args());
    }

    private static function _some($callback, $collection) {
        return call_user_func('Functional\\some', $collection, $callback);
    }

    public static function some($callback = null, $collection = null) {
        return self::curry(array('Rfp\\F', '_some'), 2, func_get_args());
    }

    private static function _sum($collection) {
        return call_user_func('Functional\\sum', $collection);
    }

    public static function sum($collection = null) {
        return self::curry(array('Rfp\\F', '_sum'), 1, func_get_args());
    }

    private static function _tail($collection) {
        return call_user_func('Functional\\tail', $collection);
    }

    public static function tail($collection = null) {
        return self::curry(array('Rfp\\F', '_tail'), 1, func_get_args());
    }

    private static function _true($collection) {
        return call_user_func('Functional\\true', $collection);
    }

    public static function true($collection = null) {
        return self::curry(array('Rfp\\F', '_true'), 1, func_get_args());
    }

    private static function _truthy($collection) {
        return call_user_func('Functional\\truthy', $collection);
    }

    public static function truthy($collection = null) {
        return self::curry(array('Rfp\\F', '_truthy'), 1, func_get_args());
    }

    private static function _unique($collection) {
        return call_user_func('Functional\\unique', $collection);
    }

    public static function unique($collection = null) {
        return self::curry(array('Rfp\\F', '_unique'), 1, func_get_args());
    }
} 