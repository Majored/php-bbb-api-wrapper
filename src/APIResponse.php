<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

class APIResponse {
    private $result;
    private $data;
    private $error;

    function __construct($result, $data, $error) {
        $this->result = $result;
        $this->data = $data;
        $this->error = $error;
    }
    
    public function getData() {
        return $this->data;
    }
    
    public function getError() {
        return $this->error;
    }

    public static function from_json($json) {
        $data = json_decode($json, true);

        if (!array_key_exists("data", $data)) {
            $data["data"] = NULL;
        }
        if (!array_key_exists("error", $data)) {
            $data["error"] = NULL;
        }

        return new APIResponse($data["result"], $data["data"], $data["error"]);
    }
}