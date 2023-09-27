<?php

declare(strict_types=1);

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
