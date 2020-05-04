<?php

namespace Lib;

use Lib\Config;

class Http
{
    private $baseUrl;
    private $endpoint;
    private $headers;
    private $method;
    private $query;

    public function __construct()
    {
        $this->baseUrl = Config::get('api.baseUrl');
        $this->query = '';
        $this->headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json'
        ];
    }

    public function post($endpoint, $data)
    {
        $this->method = 'POST';
        $this->endpoint = $endpoint;
        $this->query = http_build_query($data);

        return $this->send();
    }

    public function get($endpoint)
    {
        $this->method = 'GET';
        $this->endpoint = $endpoint;

        return $this->send();
    }

    private function send()
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->baseUrl . $this->endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl, CURLOPT_USERPWD, Config::get('api.key') . ':');
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);

        switch ($this->method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $this->query);
            break;
        }

        $data = trim(curl_exec($curl));

        if (!curl_errno($curl)) {
            $data = json_decode(utf8_encode($data), true);
            $info = curl_getinfo($curl);
            $status = 'OK';
            $errNo = '';
        } else {
            $status = 'Error';
            $errNo = curl_errno($curl);
            $data = [];
            $info = [];
        }

        curl_close($curl);

        return [
            'status' => $status,
            'errno' => $errNo,
            'info' => $info,
            'data' => $data
        ];
    }
}
