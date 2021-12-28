<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

require __DIR__ . "/APIToken.php";
require __DIR__ . "/APIResponse.php";
require __DIR__ . "/Throttler.php";

require __DIR__ . "/helpers/AlertsHelper.php";
require __DIR__ . "/helpers/ConversationsHelper.php";
require __DIR__ . "/helpers/MembersHelper.php";
require __DIR__ . "/helpers/ThreadsHelper.php";
require __DIR__ . "/helpers/ResourcesHelper.php";

class APIWrapper {
    const BASE_URL = "https://api.mc-market.org/v1";
    const CONTENT_TYPE_HEADER = "Content-Type: application/json";
    const PER_PAGE = 20;

    private $http;
    private $token;
    private $throttler;

    public function initialise(APIToken $token) {
        $this->token = $token;
        $this->http = curl_init();
        $this->throttler = new Throttler();
        
        curl_setopt($this->http, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->http, CURLOPT_HEADER, 1);
        
        return $this->health();
    }

    function get(string $endpoint) {
        $this->stallUntilCanMakeRequest(RequestType::READ);

        curl_setopt($this->http, CURLOPT_HTTPGET, true);
        curl_setopt($this->http, CURLOPT_URL, APIWrapper::BASE_URL . "/" . $endpoint);
        curl_setopt($this->http, CURLOPT_HTTPHEADER, array($this->token->as_header()));

        if ($body = $this->handleResponse(RequestType::READ)) {
            return APIResponse::from_json($body);
        } else {
            return $this->get($endpoint);
        }
    }

    function patch(string $endpoint, $body) {
        $this->stallUntilCanMakeRequest(RequestType::WRITE);

        curl_setopt($this->http, CURLOPT_HTTPGET, true);
        curl_setopt($this->http, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($this->http, CURLOPT_URL, APIWrapper::BASE_URL . "/" . $endpoint);
        curl_setopt($this->http, CURLOPT_HTTPHEADER, [$this->token->as_header(), APIWrapper::CONTENT_TYPE_HEADER]);
        curl_setopt($this->http, CURLOPT_POSTFIELDS, json_encode($body));

        if ($body = $this->handleResponse(RequestType::WRITE)) {
            return APIResponse::from_json($body);
        } else {
            return $this->patch($endpoint, $body);
        }
    }


    function post(string $endpoint, $body) {
        $this->stallUntilCanMakeRequest(RequestType::WRITE);

        curl_setopt($this->http, CURLOPT_HTTPGET, true);
        curl_setopt($this->http, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->http, CURLOPT_URL, APIWrapper::BASE_URL . "/" . $endpoint);
        curl_setopt($this->http, CURLOPT_HTTPHEADER, [$this->token->as_header(), APIWrapper::CONTENT_TYPE_HEADER]);
        curl_setopt($this->http, CURLOPT_POSTFIELDS, json_encode($body));

        if ($body = $this->handleResponse(RequestType::WRITE)) {
            return APIResponse::from_json($body);
        } else {
            return $this->post($endpoint, $body);
        }
    }


    function delete(string $endpoint) {
        $this->stallUntilCanMakeRequest(RequestType::WRITE);
        
        curl_setopt($this->http, CURLOPT_HTTPGET, true);
        curl_setopt($this->http, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($this->http, CURLOPT_URL, APIWrapper::BASE_URL . "/" . $endpoint);
        curl_setopt($this->http, CURLOPT_HTTPHEADER, array($this->token->as_header()));

        if ($body = $this->handleResponse(RequestType::WRITE)) {
            return APIResponse::from_json($body);
        } else {
            return $this->delete($endpoint);
        }
    }

    private function handleResponse(int $type) {
        list($header, $body) = explode("\r\n\r\n", curl_exec($this->http), 2);
        $status = curl_getinfo($this->http, CURLINFO_HTTP_CODE);
        $header = APIWrapper::parse_headers(explode("\r\n", $header));

        if ($status === 429 && $type = RequestType::READ) {
            $this->throttler->setRead(intval($header["Retry-After"]));
            return null;
        } else if ($status === 429 && $type = RequestType::WRITE) {
            $this->throttler->setWrite(intval($header["Retry-After"]));
            return null;
        }

        if ($type = RequestType::READ) {
            $this->throttler->resetRead();
        } else if ($type = RequestType::WRITE) {
            $this->throttler->resetWrite();
        }

        return $body;
    }

    private static function parse_headers($headers) {
        $new = [];

        foreach ($headers as $header) {
            $split = explode(":", $header, 2);
            if (count($split) == 2) {
                $new[$split[0]] = $split[1];
            }
        }

        return $new;
    }

    private function stallUntilCanMakeRequest(int $type) {
        while ($stall_for = $this->throttler->stall_for($type)) {
            usleep($stall_for * 1000);
        }
    }

    function health() {
        return $this->get("health");
    }

    function ping() {
        $start = microtime(true);
        $res = $this->health();
        $end = microtime(true);

        if ($res->getData()) {
            return new APIResponse("success", ($end - $start) * 1000, null);
        } else {
            return $res;
        }
    }

    function alerts() {
        return new AlertsHelper($this);
    }

    function conversations() {
        return new ConversationsHelper($this);
    }

    function members() {
        return new MembersHelper($this);
    }

    function threads() {
        return new ThreadsHelper($this);
    }

    function resources() {
        return new ResourcesHelper($this);
    }
}