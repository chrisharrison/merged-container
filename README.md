# merged-container

[![Build Status](https://travis-ci.org/chrisharrison/php-array-of.svg?branch=master)](https://travis-ci.org/chrisharrison/php-array-of)

A [PSR-11](http://www.php-fig.org/psr/psr-11/) compliant container that merges a set of PSR-11 containers. You might call it a container container.

## Requirements ##

Requires PHP 7.1

## Installation ##

Through Composer, obviously:

```
composer require chrisharrison/merged-container
```

## Why? ##

The [PSR-11](http://www.php-fig.org/psr/psr-11/) container interface have the following properties:

* It's immutable. That means it can't be changed after it's been instantiated.
* It doesn't provide an iteration mechanism.

These two properties mean it's difficult to create a container which is a merge of two or more PSR-11 containers.

This library provides a container which implements the PSR-11 interface. It's constructed by an array of other PSR-11 containers. These containers can use any concrete implementation ([PHP-DI](https://github.com/PHP-DI/PHP-DI), [Pimple](https://github.com/silexphp/Pimple)) as long as they implement the PSR-11 interface.

## Usage ##

Create a merged container:

```php
$merged = new MergedContainer([$container1, $container2]);
```

Use it like any other PSR-11 container.

