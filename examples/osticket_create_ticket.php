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
 * Adding named query parameters one by one
 */
$api->addHeader('X-API-Key: YOUR_API_KEY');
$api->addParam('alert', true);
$api->addParam('autorespond', true);
$api->addParam('source', 'API');
$api->addParam('name', 'Test User');
$api->addParam('email', 'user@example.com');
$api->addParam('ip', '127.0.0.1');

/**
 * Or immediately in the array
 */
$params = ['subject' => 'Testing API', 'message' => 'MESSAGE HERE'];
$api->addParam(false, $params);

/**
 * Executing the query as JSON
 */
var_dump($api->jsonRequest('https://support.example.com/api/tickets.json'));

echo 'Request response code: ' . $api->httpcode . PHP_EOL;
echo 'Request error string: ' . $api->lasterror . PHP_EOL;
