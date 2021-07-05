<?php

/**
 * Example of simple POST request using "Icemont/CurlWrapper".
 *
 * @author   Ray Icemont <ray.icemont@gmail.com>
 * @license  https://opensource.org/licenses/MIT
 * @package  curlwrapper
 */

require_once __DIR__ . '/../src/CurlWrapper.php';

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
