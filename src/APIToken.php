<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

class APIToken {
    private $type;
    private $value;

    public function __construct($type, $value) {
        $this->type = $type;
        $this->value = $value;
    }

    public function as_header() {
        return "Authorization: " . $this->type . " " . $this->value;
    }
}

class TokenType {
    public const PRIVATE = "Private";
    public const SHARED = "Shared";
}