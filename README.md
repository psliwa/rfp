# Rfp - Real functional php

This is working prototype of library that supports functional programming. It is inspired by [ramda][2] and [this talk][3], 
under hood uses [functional php][1] library.

> **IMPORTANT**
>
> This library is only a working prototype. The most of code was generated, it has not any tests and the performance 
> issues was ignored. So you can play with this library and enjoy functional programming in php, but you should not 
> use it in your project! If you like this project, give a star - this will be a sign for me, this kind of library is welcome.

# Differences

Rfp is based on [functional php][1], there are **few very important** changes:

* there are `F::compose` and `F::pipe` functions. `F::pipe` does the same as `F::compose` function, but with reversed function
order, so it is more readable.
* making `$collection` last function argument
* all functions support `autocurring`
* there are more primitives

`F::compose` and `F::pipe` allow to functions chaining. There is a simple example:

```php
    $composedFunc = F::compose($func1, $func2);
    //is the same as
    $composedFunc = function($x) use($func1, $func2){
        return $func1($func2($x));
    };
    
    $pipedFunc = F::pipe($func1, $func2);
    //is the same as
    $pipedFunc = function($x) use($func1, $func2){
        return $func2($func1($x));//unlike "compose", $func1 is invoked as first
    };
``` 

`Curry` is an operation on function that allows you to create function with smaller number of arguments.

Classical example:

```php
    $add = function($a, $b) { 
        return $a + $b; 
    };
    
    $add5 = curry($add, 5);//we have a function like: function($b) { return 5 + $b; }
    
    $add5(3);//5 + 3
```

`Autocurring` is that when function gets less arguments than it expects, it will invoke `curry` on itself.

# Consequences

* You can pass functions as values

    ```php
        $map = F::map();
        //yes, $map is F::map() function
    ```
    
* You can easily create your own functions thanks to `autocurring`

    ```php
        $idExtractor = F::map(F::prop('id'));//second argument missing - autocurring
        $ids = $idExtractor($objects);
    ```
    
* You can call every function in normal way - just pass all arguments:

    ```php
        $ids = F::map(F::prop('id'), $objects);
    ```
    
* You can easily create your own functions thanks to `F::pipe`

    ```php
        $sumQuantity = F::pipe(
            F::map(F::prop('quantity')),
            F::sum()
        );
    ```
    
* `autocurring` and `pipe` allows you to write code where functions and data are separated - thanks to that, code is more
declarative and you can take advantage from function composition.
* Usage of `Closures` minimalized to minimum

# Examples

```php

/**
 * example from @lstrojny's article about functional programming
 *
 * Task: calculate cart value
 */

$cart = array(
    array(
        'name'     => 'Item 1',
        'quantity' => 10,
        'price'    => 9.99,
    ),
    array(
        'name'     => 'Item 2',
        'quantity' => 3,
        'price'    => 5.99,
    )
);

//using functional php

$value = F\sum(
    F\zip(
        F\pluck($cart, 'price'),
        F\pluck($cart, 'quantity'),
        function($price, $quantity) {
            return $price * $quantity;
        }
    )
);

//using Rfp

$value = F::pipe(
    F::converge(
        F::zip(F::multiply()),
        F::pluck('price'),
        F::pluck('quantity')
    ),
    F::sum()
)->apply($cart);

//converge is a new primitive, simplified implementation:
function converge($then, $func1, $func2) {
    return function($x) use($then, $func1, $func2){
        return $then($func1($x), $func2($x));
    };
}

/**
 * We have an array of patients and we want to know percentage of female patients grouped by blood type.
 */

$patients = array(/*...*/);

//using functional php

$result = F\map(
    F\group(
        $patients,
        function(Patient $patient){
            return $patient->bloodType;
        }
    ),
    function(array $patientsByBloodType){
        list($femalesCount, $malesCount) = F\map(
            F\partition(
                $patientsByBloodType,
                function(Patient $patient) {
                    return $patient->sex === 'female';
                }
            ),
            function($patients){
                return count($patients);
            }
        );

        return $femalesCount / 
        	($femalesCount + $malesCount) * 100;
    }
);

//using Rfp

$percent = function($a, $b){
    return $a / ($a+$b) * 100;
};

$result = F::pipe(
    F::group(F::prop('bloodType')),
    F::map(F::pipe(
        F::partition(
            F::pipe(F::prop('sex'), F::eq('female'))
        ),
        F::map(F::unary('count')),
        F::apply($percent)
    ))
)->apply($patients);

//unary is a new primitive - wraps given function and passes only 1 argument to it
//apply is a new primitive - it acts as call_user_func_array

```
 
[1]: https://github.com/lstrojny/functional-php
[2]: https://github.com/CrossEye/ramda
[3]: https://www.youtube.com/watch?v=m3svKOdZijA
