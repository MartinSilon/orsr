<?php

namespace App\Services;


class ChatGPTService
{
    public function requestChatGPT($instructionToChatGPT)
    {

        // Get the API key from environment variables
        $apiKey = env('OPENAI_API_KEY');

        if (strpos(getcwd(), 'DEV') !== false) {
            $max_tokens = 400; // dev environment
        } else {
            $max_tokens = 2000; // PROD
        }

        $req = json_encode(
            [
                "model" => "gpt-4",
                "messages" => [
                    [
                        "role" => "system",
                        "content" => $instructionToChatGPT
                    ],
                    [
                        "role" => "user",
                        "content" => ""
                    ]
                ],
                "temperature" => 1,
                "max_tokens" => $max_tokens,
                "top_p" => 1,
                "frequency_penalty" => 0,
                "presence_penalty" => 0
            ],
            JSON_UNESCAPED_UNICODE
        );

        $authorization = "Authorization: Bearer " . $apiKey; // Use the API key from .env
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            $authorization
        ]);
        $res = curl_exec($ch);
        curl_close($ch);

        if (!$res) {
            return null; // or handle error appropriately
        } else {
            return $res;
        }
    }



}
