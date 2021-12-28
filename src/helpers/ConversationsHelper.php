<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

class ConversationsHelper {
    private $wrapper;

    function __construct(APIWrapper $wrapper) {
        $this->wrapper = $wrapper;
    }

    public function list_unread() {
        return $this->wrapper->get("conversations");
    }

    public function list_replies(int $conversation_id) {
        return $this->wrapper->get("conversations/" . $conversation_id . "/replies");
    }
}