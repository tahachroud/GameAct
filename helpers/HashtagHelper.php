<?php

class HashtagHelper
{
    private static $file = __DIR__ . '/../public/hashtags.json';

    /**
     * Extract hashtags from text
     */
    public static function extract(string $content): array
    {
        preg_match_all('/#([a-zA-Z0-9_]+)/', $content, $matches);

        if (empty($matches[1])) {
            return [];
        }

        // normalize: lowercase + unique
        return array_unique(array_map('strtolower', $matches[1]));
    }

    /**
     * Store hashtags in JSON (increment counters)
     */
    public static function store(array $hashtags): void
    {
        if (empty($hashtags)) return;

        if (!file_exists(self::$file)) {
            file_put_contents(self::$file, json_encode([]));
        }

        $data = json_decode(file_get_contents(self::$file), true);
        if (!is_array($data)) $data = [];

        foreach ($hashtags as $tag) {
            $data[$tag] = ($data[$tag] ?? 0) + 1;
        }

        file_put_contents(
            self::$file,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
    }
}
