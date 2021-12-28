<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

class Throttler {
    private $read_last_request;
    private $read_last_retry;

    private $write_last_request;
    private $write_last_retry;

    function __construct() {
        $this->read_last_request = microtime(true) * 1000;
        $this->read_last_retry = 0;

        $this->write_last_request = microtime(true) * 1000;
        $this->write_last_retry = 0;
    }

    function setRead(int $retry) {
        $this->read_last_retry = $retry;
        $this->read_last_request = microtime(true) * 1000;
    }

    function resetRead() {
        $this->read_last_retry = 0;
        $this->read_last_request = microtime(true) * 1000;
    }

    function setWrite(int $retry) {
        $this->write_last_retry = $retry;
        $this->write_last_request = microtime(true) * 1000;
    }

    function resetWrite() {
        $this->write_last_retry = 0;
        $this->write_last_request = microtime(true) * 1000;
    }

    function stall_for(int $type) {
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

class RequestType {
    public const READ = 0;
    public const WRITE = 1;
}