<?php

/**
 * Example of creating a ticket through the API for osTicket using "Icemont/CurlWrapper".
 *
 * @author   Ray Icemont <ray.icemont@gmail.com>
 * @license  https://opensource.org/licenses/MIT
 * @package  curlwrapper
 */

require_once __DIR__ . '/../src/CurlWrapper.php';

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
