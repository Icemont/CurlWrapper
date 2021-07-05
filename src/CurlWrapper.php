<?php

/**
 * Simplest OOP CURL Wrapper
 *
 * @author   Ray Icemont <ray.icemont@gmail.com>
 * @license  https://opensource.org/licenses/MIT
 * @package  curlwrapper
 */

namespace Icemont\cURL;

use InvalidArgumentException;

class CurlWrapper
{
    public $httpcode;
    public $lasterror;

    private $params = [];
    private $headers = [];

    private $config = [
        'user_agent' => 'Mozilla/5.0 (compatible; CurlWrapper/1.1; +https://github.com/Icemont/CurlWrapper)',
        'timeout' => 30,
        'referer' => false,
    ];


    /**
     * Adding new parameters for POST request.
     * If no parameter name is specified the new array with values does not override previously set values with matching parameter names.
     *
     * @param mixed $param_name parameter name
     * @param mixed $param_value parameter value(s)
     * @return void
     */
    public function addParam($param_name, $param_value): void
    {
        if ($param_name !== false && $param_name !== null) {
            $this->params[$param_name] = $param_value;
        } elseif (is_array($param_value)) {
            $this->params += $param_value;
        } else {
            throw new InvalidArgumentException('The parameter must have a name or be an array!');
        }
    }

    /**
     * Adds new headers to future requests.
     *
     * @param string $new_header
     * @return void
     */
    public function addHeader(string $new_header): void
    {
        if (!empty($new_header)) {
            $this->headers[] = $new_header;
        } else {
            throw new InvalidArgumentException('The header cannot be empty!');
        }
    }

    /**
     * Resets all previously set parameters and headers.
     *
     * @return void
     */
    public function reset(): void
    {
        $this->params = array();
        $this->headers = array();
    }

    /**
     * Alias for $this->request to send POST request as JSON.
     *
     * @param $url
     * @return mixed
     */
    public function jsonRequest($url)
    {
        return $this->request($url, true, true);
    }

    /**
     * Setting Timeout for future requests
     *
     * @param int $timeout
     * @return void
     */
    public function setTimeout(int $timeout): void
    {
        if ($timeout) {
            $this->config['timeout'] = $timeout;
        } else {
            throw new InvalidArgumentException('The parameter must be an integer greater than 0!');
        }
    }

    /**
     * Setting User-Agent for future requests
     *
     * @param string $user_agent
     * @return void
     */
    public function setUserAgent(string $user_agent): void
    {
        $this->config['user_agent'] = $user_agent;
    }

    /**
     * Setting Referer for future requests
     *
     * @param string $referer
     * @return void
     */
    public function setReferer(string $referer): void
    {
        if (preg_match('|^http[s]?://|i', $referer)) {
            $this->config['referer'] = $referer;
        } elseif (empty($referer)) {
            $this->config['referer'] = false;
        } else {
            throw new InvalidArgumentException('The parameter must be a link or an empty string to reset the value!');
        }
    }

    /**
     * Executes the request.
     * Request is always executed as POST if the parameter array is not empty.
     *
     * @param string $url
     * @param bool $post_request make a POST request
     * @param bool $as_json send POST request as JSON
     * @return bool|string
     */
    public function request(string $url, bool $post_request = false, bool $as_json = false)
    {
        $headers = array();
        if ($as_json && !in_array('Content-Type: application/json', $this->headers)) {
            $headers[] = 'Content-Type: application/json';
        }

        if (count($this->headers)) {
            foreach ($this->headers as $q_header) {
                $headers[] = $q_header;
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->config['timeout']);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->config['user_agent']);
        if ($this->config['referer']) {
            curl_setopt($ch, CURLOPT_REFERER, $this->config['referer']);
        }
        if (count($this->params)) {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($as_json) {
                $post_data = json_encode($this->params);
            } else {
                $post_data = $this->params;
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        } elseif ($post_request) {
            curl_setopt($ch, CURLOPT_POST, true);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        $this->httpcode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $this->lasterror = curl_error($ch);

        curl_close($ch);

        if ($response) {
            return $response;
        }

        return false;
    }
}
