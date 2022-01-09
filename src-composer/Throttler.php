<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

/** Stores metadata needed for local request throttling. */
class Throttler {
    /** @var int The millisecond timestamp of the last read request. */
    private $read_last_request;

    /** @var int The amount of milliseconds to stall for before making another read request. */
    private $read_last_retry;

    /** @var int The millisecond timestamp of the last write request. */
    private $write_last_request;

    /** @var int The amount of milliseconds to stall for before making another write request. */
    private $write_last_retry;


    /**
	 * Constructs a new instance by setting default values for all properties.
	 */
    function __construct() {
        $this->read_last_request = microtime(true) * 1000;
        $this->read_last_retry = 0;

        $this->write_last_request = microtime(true) * 1000;
        $this->write_last_retry = 0;
    }

    /**
	 * Sets a read retry amount and updates the read request time.
	 *
	 * @param int The amount of milliseconds to wait.
	 */
    function setRead(int $retry) {
        $this->read_last_retry = $retry;
        $this->read_last_request = microtime(true) * 1000;
    }

    /**
	 * Resets the read retry amount to zero and updates the read request time.
	 */
    function resetRead() {
        $this->read_last_retry = 0;
        $this->read_last_request = microtime(true) * 1000;
    }

    /**
	 * Sets a write retry amount and updates the write request time.
	 *
	 * @param int The amount of milliseconds to wait.
	 */
    function setWrite(int $retry) {
        $this->write_last_retry = $retry;
        $this->write_last_request = microtime(true) * 1000;
    }

    /**
	 * Resets the write retry amount to zero and updates the write request time.
	 */
    function resetWrite() {
        $this->write_last_retry = 0;
        $this->write_last_request = microtime(true) * 1000;
    }

    /**
	 * Calculates the number of milliseconds, if any, a request would need to stall for.
	 *
     * @param int The type of request which the response originated from (RequestType).
	 * @return int The number of milliseconds to wait.
	 */
    function stallFor(int $type): int {
        $time = microtime(true) * 1000;
        $stall_for = 0;

        if ($type == RequestType::READ) {
            if ($this->read_last_retry > 0 && ($time - $this->read_last_request) < $this->read_last_retry) {
                $stall_for = $this->read_last_retry - ($time - $this->read_last_request);
            }
        }

        if ($type == RequestType::WRITE) {
            if ($this->write_last_retry > 0 && ($time - $this->write_last_request) < $this->write_last_retry) {
                $stall_for = $this->write_last_retry - ($time - $this->write_last_request);
            }
        }

        return $stall_for;
    }
}

/** Holds declarations for different request types. */
class RequestType {
    /** @var int An integer value representing the read endpoints (ie. GET). */
    public const READ = 0;

    /** @var int An integer value representing the write endpoints (ie. POST, PATCH, & DELETE). */
    public const WRITE = 1;
}