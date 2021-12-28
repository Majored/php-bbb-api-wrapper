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

    public function create(string $title, string $message, array $recipients) {
        $body = ["title" => $title, "message" => $message, "recipient_ids" => $recipients];
        return $this->wrapper->post("conversations", $body);
    }

    public function list_replies(int $conversation_id) {
        return $this->wrapper->get("conversations/" . $conversation_id . "/replies");
    }

    public function reply(int $conversation_id, string $message) {
        return $this->wrapper->post("conversations/" . $conversation_id . "/replies", ["message" => $message]);
    }
}