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
    private int $lastCode;

    private string $lastError;

    private array $data = [];

    private array $headers = [];

    private array $config = [
        'user_agent' => 'Mozilla/5.0 (compatible; CurlWrapper/2.0; +https://github.com/Icemont/CurlWrapper)',
        'timeout' => 30,
        'referer' => false,
    ];

    public function jsonRequest(string $url): bool|string
    {
        return $this->request($url, true, true);
    }

    public function postRequest(string $url): bool|string
    {
        return $this->request($url, true);
    }

    public function getRequest(string $url): bool|string
    {
        return $this->request($url);
    }

    public function addData(string $key, string $value): static
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function addDataFromArray(array $data): static
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    public function addHeader(string $header): static
    {
        if (empty($header)) {
            throw new InvalidArgumentException('The header cannot be empty');
        }

        $this->headers[] = $header;

        return $this;
    }

    public function reset(): void
    {
        $this->data = [];
        $this->headers = [];
    }

    public function setTimeout(int $timeout): static
    {
        if ($timeout <= 0) {
            throw new InvalidArgumentException('The parameter must be an integer greater than 0');
        }

        $this->config['timeout'] = $timeout;

        return $this;
    }

    public function setUserAgent(string $userAgent): static
    {
        $this->config['user_agent'] = $userAgent;

        return $this;
    }

    public function setReferer(string $referer): static
    {
        if (!preg_match('|^http[s]?://|i', $referer)) {
            throw new InvalidArgumentException('The referer parameter must be a link');
        }

        $this->config['referer'] = $referer;

        return $this;
    }

    public function resetReferer(): static
    {
        $this->config['referer'] = false;

        return $this;
    }

    private function request(string $url, bool $asPostRequest = false, bool $asJson = false): bool|string
    {
        $headers = $this->headers;

        if ($asJson && !in_array('Content-Type: application/json', $this->headers)) {
            $headers[] = 'Content-Type: application/json';
        }

        $ch = curl_init();

        if ($asPostRequest) {
            curl_setopt($ch, CURLOPT_POST, true);

            if ($asJson) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->data));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
            }
        } elseif (count($this->data)) {
            $url = rtrim($url, '?&');
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($this->data);
        }

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

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        $this->lastCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $this->lastError = curl_error($ch);

        curl_close($ch);

        return $response;
    }

    public function getLastCode(): int
    {
        return $this->lastCode;
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }
}
