<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

class ResourcesHelper {
    private $wrapper;

    function __construct(APIWrapper $wrapper) {
        $this->wrapper = $wrapper;
    }

    public function list() {
        return $this->wrapper->get("resources");
    }

    public function list_owned() {
        return $this->wrapper->get("resources/owned");
    }

    public function list_collaborated() {
        return $this->wrapper->get("resources/collaborated");
    }

    public function fetch(int $resource_id) {
        return $this->wrapper->get("resources/" . $resource_id);
    }
}