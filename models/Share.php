<?php

class Share {

    private $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../public/admin_shares.json';

        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([
                "total_shares" => 0,
                "posts" => []
            ]));
        }
    }

    private function load()
    {
        return json_decode(file_get_contents($this->file), true);
    }

    private function save($data)
    {
        file_put_contents($this->file, json_encode($data));
    }

    public function updateShare($postId)
    {
        $data = $this->load();

        if (!isset($data['posts'][$postId])) {
            $data['posts'][$postId] = 0;
        }

        // ADD SHARE
        $data['posts'][$postId]++;
        $data['total_shares']++;

        $this->save($data);

        return $data;
    }

    public function getTotal()
    {
        $data = $this->load();
        return $data['total_shares'];
    }

    public function getPostShares($postId)
    {
        $data = $this->load();
        return $data['posts'][$postId] ?? 0;
    }

    public function getData()
    {
        return $this->load();
    }
}
