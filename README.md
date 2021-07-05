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
 * Changing the configuration parameters
 */
$curl->setTimeout(5);
$curl->setUserAgent('Mozilla/5.0 (compatible; CurlWrapper/1.1)');
$curl->setReferer('https://example.com/');

/*
 * Adding the header and parameters
 */
$curl->addHeader('API-Key: TEST_KEY');
$curl->addParam('test', 'value');
$curl->addParam('param2', 'value2');

/**
 * Executing the query
 */
var_dump($curl->request('https://httpbin.org/post'));

echo 'Request response code: ' . $curl->httpcode . PHP_EOL;
echo 'Request error string: ' . $curl->lasterror . PHP_EOL;
```

### Simple POST and GET requests
```php
use Icemont\cURL\CurlWrapper;

$curl = new CurlWrapper();

// Executing an empty POST request
var_dump($curl->request('http://example.com/post', true));

// Add data and execute a POST request with data sending
$curl->addParam('data', array('foo' => 'bar'));
$curl->addParam('int', 1);
var_dump($curl->request('http://example.com/post'));


// Reset the data and execute a simple GET request
$curl->reset();
var_dump($curl->request('http://example.com/'));

```
### Example of new ticket creation via the osTicket API

> **File:** examples/osticket_create_ticket.php

```php
use Icemont\cURL\CurlWrapper;

$api = new CurlWrapper();

// Adding named query parameters one by one
$api->addHeader('X-API-Key: YOUR_API_KEY');
$api->addParam('alert', true);
$api->addParam('autorespond', true);
$api->addParam('source', 'API');
$api->addParam('name', 'Test User');
$api->addParam('email', 'user@example.com');
$api->addParam('ip', '127.0.0.1');

// Or immediately in the array
$params = ['subject' => 'Testing API', 'message' => 'MESSAGE HERE'];
$api->addParam(false, $params);


//Executing the query as JSON
var_dump($api->jsonRequest('https://support.example.com/api/tickets.json'));

// Print the response code
echo 'Request response code: ' . $api->httpcode . PHP_EOL;
// Print the error string
echo 'Request error string: ' . $api->lasterror . PHP_EOL;

```
## Contact

Open an issue on GitHub if you have any problems or suggestions.

## License

The contents of this repository is released under the [MIT license](https://opensource.org/licenses/MIT).
