<?php
// Copyright (c) 2021 Harry [Majored] [hello@majored.pw]
// MIT License (https://github.com/Majored/php-mcm-api-wrapper/blob/main/LICENSE)

class MembersHelper {
    private $wrapper;

    function __construct(APIWrapper $wrapper) {
        $this->wrapper = $wrapper;
    }

    public function fetch(int $member_id) {
        return $this->wrapper->get("members/" . $member_id);
    }

    public function fetch_self() {
        return $this->wrapper->get("members/self");
    }

    public function fetch_by_name(string $username) {
        return $this->wrapper->get("members/username/" . $username);
    }

    public function modify_self(string $custom_title, string $about_me, string $signature) {
        $body = ["custom_tile" => $custom_title, "about_me" => $about_me, "signature" => $signature];
        return $this->wrapper->patch("members/self", $body);
    }

    public function list_recent_bans() {
        return $this->wrapper->get("members/bans");
    }

    public function list_profile_posts() {
        return $this->wrapper->get("members/self/profile-posts");
    }

    public function fetch_profile_post(int $profile_post_id) {
        return $this->wrapper->get("members/self/profile-posts/" . $profile_post_id);
    }

    public function modify_profile_post(int $profile_post_id, string $message) {
        return $this->wrapper->patch("members/self/profile-posts/" . $profile_post_id, ["message" => $message]);
    }

    public function delete_profile_post(int $profile_post_id) {
        return $this->wrapper->delete("members/self/profile-posts/" . $profile_post_id);
    }
}