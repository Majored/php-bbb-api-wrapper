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
        while ($stall_for = $this->throttler->stall_for(RequestType::READ)) {
            usleep($stall_for * 1000);
        }

        curl_setopt($this->http, CURLOPT_HTTPGET, true);
        curl_setopt($this->http, CURLOPT_URL, APIWrapper::BASE_URL . "/" . $endpoint);
        curl_setopt($this->http, CURLOPT_HTTPHEADER, array($this->token->as_header()));
        $status = curl_getinfo($this->http, CURLINFO_HTTP_CODE);
        list($header, $body) = explode("\r\n\r\n", curl_exec($this->http), 2);
        $header = $this->parse_headers(explode("\r\n", $header));

        if ($status === 429) {
            $this->throttler->setRead(intval($header["Retry-After"]));
            return $this->get($endpoint);
        }

        $this->throttler->resetRead();
        return APIResponse::from_json($body);
    }

    function patch(string $endpoint, $body) {
        while ($stall_for = $this->throttler->stall_for(RequestType::WRITE)) {
            usleep($stall_for * 1000);
        }

        curl_setopt($this->http, CURLOPT_HTTPGET, true);
        curl_setopt($this->http, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($this->http, CURLOPT_URL, APIWrapper::BASE_URL . "/" . $endpoint);
        curl_setopt($this->http, CURLOPT_HTTPHEADER, [$this->token->as_header(), APIWrapper::CONTENT_TYPE_HEADER]);
        curl_setopt($this->http, CURLOPT_POSTFIELDS, json_encode($body));

        return APIResponse::from_json(curl_exec($this->http));
    }


    function post(string $endpoint, $body) {
        while ($stall_for = $this->throttler->stall_for(RequestType::WRITE)) {
            usleep($stall_for * 1000);
        }

        curl_setopt($this->http, CURLOPT_HTTPGET, true);
        curl_setopt($this->http, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->http, CURLOPT_URL, APIWrapper::BASE_URL . "/" . $endpoint);
        curl_setopt($this->http, CURLOPT_HTTPHEADER, [$this->token->as_header(), APIWrapper::CONTENT_TYPE_HEADER]);
        curl_setopt($this->http, CURLOPT_POSTFIELDS, json_encode($body));

        return APIResponse::from_json(curl_exec($this->http));
    }


    function delete(string $endpoint) {
        while ($stall_for = $this->throttler->stall_for(RequestType::WRITE)) {
            usleep($stall_for * 1000);
        }
        
        curl_setopt($this->http, CURLOPT_HTTPGET, true);
        curl_setopt($this->http, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($this->http, CURLOPT_URL, APIWrapper::BASE_URL . "/" . $endpoint);
        curl_setopt($this->http, CURLOPT_HTTPHEADER, array($this->token->as_header()));

        return APIResponse::from_json(curl_exec($this->http));
    }

    function parse_headers($headers) {
        $new = [];

        foreach ($headers as $header) {
            $split = explode(":", $header, 2);
            if (count($split) == 2) {
                $new[$split[0]] = $split[1];
            }
        }

        return $new;
    }

    public function health() {
        return $this->get("health");
    }

    public function ping() {
        $start = microtime(true);
        $res = $this->health();
        $end = microtime(true);

        if ($res->getData()) {
            return new APIResponse("success", ($end - $start) * 1000, null);
        } else {
            return $res;
        }
    }

    public function alerts() {
        return new AlertsHelper($this);
    }

    public function conversations() {
        return new ConversationsHelper($this);
    }

    public function members() {
        return new MembersHelper($this);
    }

    public function threads() {
        return new ThreadsHelper($this);
    }

    public function resources() {
        return new ResourcesHelper($this);
    }
}