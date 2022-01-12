<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

require __DIR__ . "/../src/APIWrapper.php";

$token = new APIToken(TokenType::PRIVATE, "...");
$wrapper = new APIWrapper();

if ($error = $wrapper->initialise($token, true)->getError()) {
    die("API initialisation error: ". $error["message"]);
}

// A valid wrapper.