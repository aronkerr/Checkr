# Checkr

## Introduction

This module provides a simple wrapper for the Checkr service.

## Installation

Install the module using Composer into your application's vendor directory. Add the following line to your
`composer.json`.

```json
{
    "require": {
        "aronkerr/checkr": "dev-master"
    }
}
```

## Configuration

None at this point

## Basic Usage

You can get the Checkr class from anywhere anywhere in your application (e.g. controller
classes). The following example instantiates the Checkr class.

```php
use AronKerr\Checkr\Checkr;

public function indexAction()
{
    $checkr = new Checkr('xxxxxxx'); // Replace with your API key
}
```

## Create Candidate

```php
use AronKerr\Checkr\Checkr;

public function indexAction()
{
    $checkr = new Checkr('xxxxxxx'); // Replace with your API key
    $parameters = new Parameters(array(
        'first_name' => 'Bob',
        'last_name' => 'Jones',
        'email' => 'bob@gmail.com',
        'phone' => '123-456-7890',
        'zipcode' => '97222',
        'copy_requested' => 'false',
        'dob' => '1986-12-08'
    ));
    $response = $checkr->createCandidate($parameters);
}
```

## Links

* [Chekr official docs](https://docs.checkr.com)
