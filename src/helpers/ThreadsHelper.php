<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

class ThreadsHelper {
    private $wrapper;

    function __construct(APIWrapper $wrapper) {
        $this->wrapper = $wrapper;
    }

    public function list() {
        return $this->wrapper->get("threads");
    }

    public function list_replies(int $thread_id) {
        return $this->wrapper->get("threads/" . $thread_id . "/replies");
    }
}