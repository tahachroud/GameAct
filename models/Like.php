<?php

class Like {

    private $file;

    public function __construct()
    {
        // Path to JSON file
        $this->file = __DIR__ . '/../public/admin_likes.json';

        // Create file if missing
        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([
                "total_likes" => 0,
                "posts" => []
            ]));
        }
    }

    // LOAD JSON AS ARRAY
    private function load()
    {
        return json_decode(file_get_contents($this->file), true);
    }

    // SAVE ARRAY AS JSON
    private function save($data)
    {
        file_put_contents($this->file, json_encode($data));
    }

    // UPDATE LIKE
    public function updateLike($postId, $liked)
    {
        $data = $this->load();

        if (!isset($data['posts'][$postId])) {
            $data['posts'][$postId] = 0;
        }

        if ($liked) {
            $data['posts'][$postId]++;
            $data['total_likes']++;
        } else {
            if ($data['posts'][$postId] > 0) {
                $data['posts'][$postId]--;
                $data['total_likes']--;
            }
        }

        $this->save($data);

        return $data;
    }

    // GET TOTAL LIKES
    public function getTotal()
    {
        $data = $this->load();
        return $data['total_likes'];
    }

    // GET POST LIKE COUNT
    public function getPostLikes($postId)
    {
        $data = $this->load();
        return $data['posts'][$postId] ?? 0;
    }

    // GET ALL (USED IN DASHBOARD)
    public function getData()
    {
        return $this->load();
    }
}
