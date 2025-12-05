<?php

class TTSService {

    public function generateAudio($text)
    {
        $text = urlencode($text);

        // Google Translate TTS (free, stable)
        $url = "https://translate.google.com/translate_tts?ie=UTF-8&q={$text}&tl=en&client=tw-ob";

        // INIT CURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); // required by Google

        $audio = curl_exec($ch);
        curl_close($ch);

        return base64_encode($audio);
    }
}
