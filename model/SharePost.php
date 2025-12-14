<?php

class SharePost {

    private $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../public/shared_posts.json';

        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([
                "shared_posts" => []
            ], JSON_PRETTY_PRINT));
        }
    }

    private function load()
    {
        return json_decode(file_get_contents($this->file), true);
    }

    private function save($data)
    {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function createSharedPost($userId, $originalPostId, $message = "")
    {
        $data = $this->load();

        $shared = [
            "id" => time(),
            "user_id" => $userId,
            "original_post_id" => $originalPostId,
            "message" => $message,
            "date" => date("Y-m-d H:i:s")
        ];

        $data["shared_posts"][] = $shared;

        $this->save($data);

        return $shared;
    }

    public function getAll()
    {
        $data = $this->load();
        return $data["shared_posts"] ?? [];
    }
}
