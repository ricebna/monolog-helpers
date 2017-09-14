# Monolog Helpers #

This repo contains various helpers for use with monolog. For use with PHP 7.0 and Monolog 2.0.

[![Build Status](https://travis-ci.org/twistersfury/monolog-helpers.svg?branch=master)](https://travis-ci.org/twistersfury/monolog-helpers)

## Processors ##

#### GlobalsProcessor ####
Creates a new record entry of 'php_globals'. This record will include all (or specified) $_ global variables
in the current execution. Useful for debugging.

**All Globals**
```php
use TwistersFury\Monolog\Processors\GlobalsProcessor;
$processor = new GlobalsProcessor();
```

**Specified Globals**
```php
use TwistersFury\Monolog\Processors\GlobalsProcessor;
$processor = new GlobalsProcessor(
    [
        'post'    => true,
        'get'     => false,
        'request' => false,
        'server'  => true
    ]
);
```

#### BacktraceProcessor ####
Creates a new record entry of 'trace'. This record will include the stack trace. It will automatically exclude the most
recent entries to ensure you don't get additional useless stack information. This exclusion is controllable on the 
construct.

**Without Skip**
```php
use TwistersFury\Monolog\Processors\BacktraceProcessor;

$processor = new BacktraceProcessor();
```

**With Skip**
```php
use TwistersFury\Monolog\Processors\BacktraceProcessor;

//The five most recent stacks will be ignored.
$processor = new BacktraceProcessor(5);
```
