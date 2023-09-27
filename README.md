# CurlWrapper

[![Version](https://poser.pugx.org/icemont/curlwrapper/version)](//packagist.org/packages/icemont/curlwrapper)
[![License](https://poser.pugx.org/icemont/curlwrapper/license)](//packagist.org/packages/icemont/curlwrapper)

The simplest and smallest OOP wrapper for PHP cURL **with no overhead**.  
Designed to test and work with simple RestFull and JSON APIs and execute simple queries.

## Installation

	$ composer require icemont/curlwrapper

## Usage
### Example POST request with custom header and data

> **File:** examples/simple_request.php
```php
use Icemont\cURL\CurlWrapper;

$curl = new CurlWrapper();

/*
 * Preparing request
 */

$curl->setTimeout(5)
     ->setUserAgent('Mozilla/5.0 (compatible; CurlWrapper/2.0)')
     ->setReferer('https://example.com/')
     ->addHeader('API-Key: TEST_KEY')
     ->addData('test', 'value')
     ->addData('param2', 'value2')
     ->addDataFromArray([
        'fromArray1' => 'valueA',
        'fromArray2' => 'valueB',
    ]);

/**
 * Executing the query
 */
var_dump($curl->postRequest('https://httpbin.org/post'));

echo 'Request response code: ' . $curl->getLastCode() . PHP_EOL;
echo 'Request error string: ' . $curl->getLastError() . PHP_EOL;

```

### Simple POST and GET requests
```php
use Icemont\cURL\CurlWrapper;

$curl = new CurlWrapper();

// Executing an empty POST request
var_dump($curl->postRequest('https://httpbin.org/post'));

// Add data and execute a POST request with data sending
$curl->addData('value', 'test')
     ->addData('value2', 'test2');
var_dump($curl->postRequest('https://httpbin.org/post'));


// Execute a simple GET request (data will be added as Query String)
var_dump($curl->getRequest('https://httpbin.org/get'));

// Reset the data and execute a simple GET request again
$curl->reset();
var_dump($curl->getRequest('https://httpbin.org/get'));

```
### Example of new ticket creation via the osTicket API

> **File:** examples/osticket_create_ticket.php

```php
use Icemont\cURL\CurlWrapper;

$api = new CurlWrapper();

/**
 * Adding data one by one
 */
$api->addHeader('X-API-Key: YOUR_API_KEY')
    ->addData('source', 'API')
    ->addData('name', 'Test User')
    ->addData('email', 'user@example.com')
    ->addData('ip', '127.0.0.1');

/**
 * Or immediately in the array
 */
$api->addDataFromArray([
    'alert' => true,
    'autorespond' => true,
    'subject' => 'Testing API',
    'message' => 'MESSAGE HERE',
]);

/**
 * Executing the query as JSON
 */
var_dump($api->jsonRequest('https://support.example.com/api/tickets.json'));

echo 'Request response code: ' . $api->getLastCode() . PHP_EOL;
echo 'Request error string: ' . $api->getLastError() . PHP_EOL;
```
## Contact

Open an issue on GitHub if you have any problems or suggestions.

## License

The contents of this repository is released under the [MIT license](https://opensource.org/licenses/MIT).
