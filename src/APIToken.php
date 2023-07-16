<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-bbb-api-wrapper/blob/main/LICENSE)
namespace Majored\PhpBbbApiWrapper;

/** Stores data about a particular API token. */
class APIToken {
    /** @var string A string value representing this token's type. */
    private $type;

    /** @var string A string value representing this token's value. */
    private $value;


    /**
	 * Constructs a new token from the provided type and value.
     * 
     * @param string The token type (TokenType).
     * @param string The token value.
	 */
    function __construct(string $type, string $value) {
        $this->type = $type;
        $this->value = $value;
    }

    /**
	 * Returns this token as a complete Authorization header.
     * 
     * @return string The complete header line.
	 */
    function asHeader(): string {
        return sprintf("Authorization: %s %s", $this->type, $this->value);
    }
}
