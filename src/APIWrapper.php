<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

require __DIR__ . "/APIToken.php";
require __DIR__ . "/APIResponse.php";

require __DIR__ . "/helpers/AlertsHelper.php";
require __DIR__ . "/helpers/ConversationsHelper.php";
require __DIR__ . "/helpers/MembersHelper.php";
require __DIR__ . "/helpers/ThreadsHelper.php";

class APIWrapper {
    const BASE_URL = "https://api.mc-market.org/v1";
    const PER_PAGE = 20;

    private $http;
    
    public function initialise(APIToken $token) {
        $this->http = curl_init();
        
        curl_setopt($this->http, CURLOPT_HTTPHEADER, array($token->as_header()));
        curl_setopt($this->http, CURLOPT_RETURNTRANSFER, true);
        
        return $this->health();
    }

    function get(string $endpoint) {
        curl_setopt($this->http, CURLOPT_URL, APIWrapper::BASE_URL . "/" . $endpoint);
        curl_setopt($this->http, CURLOPT_HTTPGET, true);
        
        return APIResponse::from_json(curl_exec($this->http));
    }

    public function health() {
        return $this->get("health");
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