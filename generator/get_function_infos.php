<?php

use Functional as F;

function get_function_infos($src) {

    $functions = array();

    $iter = new FilesystemIterator($src);

    foreach($iter as $file) {
        $functionName = sprintf('Functional\\%s', strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $file->getBasename('.php'))));

        if($functionName === 'Functional\\zip') continue;

        try {
            $function = new \ReflectionFunction($functionName);

            $collectionParameter = F\first($function->getParameters(), function(\ReflectionParameter $parameter){
                return $parameter->getName() === 'collection';
            });

            if($collectionParameter !== null) {

                $originalParameters = F\map(F\filter($function->getParameters(), function(\ReflectionParameter $parameter){
                    return !$parameter->isOptional();
                }), function(\ReflectionParameter $parameter){
                    return '$'.$parameter->getName();
                });

                $parameters = F\map(F\filter($function->getParameters(), function(\ReflectionParameter $parameter){
                    return !$parameter->isOptional() && $parameter->getName() !== 'collection';
                }), function(\ReflectionParameter $parameter){
                    return '$'.$parameter->getName();
                });

                $parameters[] = '$collection';

                $name = substr($function->getName(), strpos($function->getName(), '\\') + 1);

                $functions[] = array(
                    '%type%' => 'private',
                    '%name%' => $name,
                    '%parameters%' => join(', ', $parameters),
                    '%original_name%' => str_replace('\\', '\\\\', $function->getName()),
                    '%original_parameters%' => join(', ', $originalParameters),
                );

                $functions[] = array(
                    '%type%' => 'public',
                    '%name%' => $name,
                    '%arity%' => count($parameters),
                    '%parameters%' => join(', ', F\map($parameters, function($parameter){
                        return $parameter.' = null';
                    }))
                );
            }
        } catch (\ReflectionException $e) {
        }
    }

    return $functions;
}