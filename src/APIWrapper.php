<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

require __DIR__ . "/APIToken.php";
require __DIR__ . "/APIResponse.php";

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
    
    public function initialise(APIToken $token) {
        $this->token = $token;
        $this->http = curl_init();
        
        curl_setopt($this->http, CURLOPT_RETURNTRANSFER, true);
        
        return $this->health();
    }

    function get(string $endpoint) {
        curl_setopt($this->http, CURLOPT_HTTPGET, true);
        curl_setopt($this->http, CURLOPT_URL, APIWrapper::BASE_URL . "/" . $endpoint);
        curl_setopt($this->http, CURLOPT_HTTPHEADER, array($this->token->as_header()));
        
        return APIResponse::from_json(curl_exec($this->http));
    }

    function patch(string $endpoint, $body) {
        curl_setopt($this->http, CURLOPT_HTTPGET, true);
        curl_setopt($this->http, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($this->http, CURLOPT_URL, APIWrapper::BASE_URL . "/" . $endpoint);
        curl_setopt($this->http, CURLOPT_HTTPHEADER, [$this->token->as_header(), APIWrapper::CONTENT_TYPE_HEADER]);
        curl_setopt($this->http, CURLOPT_POSTFIELDS, json_encode($body));

        return APIResponse::from_json(curl_exec($this->http));
    }


    function post(string $endpoint, $body) {
        curl_setopt($this->http, CURLOPT_HTTPGET, true);
        curl_setopt($this->http, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->http, CURLOPT_URL, APIWrapper::BASE_URL . "/" . $endpoint);
        curl_setopt($this->http, CURLOPT_HTTPHEADER, [$this->token->as_header(), APIWrapper::CONTENT_TYPE_HEADER]);
        curl_setopt($this->http, CURLOPT_POSTFIELDS, json_encode($body));

        return APIResponse::from_json(curl_exec($this->http));
    }


    function delete(string $endpoint) {
        curl_setopt($this->http, CURLOPT_HTTPGET, true);
        curl_setopt($this->http, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($this->http, CURLOPT_URL, APIWrapper::BASE_URL . "/" . $endpoint);
        curl_setopt($this->http, CURLOPT_HTTPHEADER, array($this->token->as_header()));

        return APIResponse::from_json(curl_exec($this->http));
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