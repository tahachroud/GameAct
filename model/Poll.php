<?php

class Poll {

    private $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../public/polls.json';

        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode(["polls" => []], JSON_PRETTY_PRINT));
        }
    }

    private function load()
    {
        $content = @file_get_contents($this->file);
        $data = json_decode($content, true);
        if (!is_array($data) || !isset($data['polls'])) return ["polls" => []];
        return $data;
    }

    private function save($data)
    {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function create($question, $options = [], $isActive = true)
    {
        $data = $this->load();

        $poll = [
            "id" => uniqid('poll_', true),
            "question" => $question,
            "options" => [],
            "is_active" => $isActive,
            "created_at" => date('Y-m-d H:i:s'),
        ];

        foreach ($options as $option) {
            $poll["options"][$option] = 0;
        }

        array_unshift($data['polls'], $poll);
        $data['polls'] = array_slice($data['polls'], 0, 10);

        $this->save($data);
        return $poll;
    }

    public function vote($pollId, $option)
    {
        $data = $this->load();

        foreach ($data['polls'] as &$poll) {
            if ($poll['id'] === $pollId && $poll['is_active']) {
                if (isset($poll['options'][$option])) {
                    $poll['options'][$option]++;
                    $this->save($data);
                    return $poll;
                }
            }
        }

        return null;
    }

    public function getActive()
    {
        $data = $this->load();

        foreach ($data['polls'] as $poll) {
            if ($poll['is_active']) {
                return $poll;
            }
        }

        return null;
    }

    public function getTotalVotes($pollId)
    {
        $data = $this->load();

        foreach ($data['polls'] as $poll) {
            if ($poll['id'] === $pollId) {
                return array_sum($poll['options']);
            }
        }

        return 0;
    }
}
