<?php

class OpenAI {
    private $api_key;
    private $model;
    private $prompt;

    function __construct($api_key, $model) {
        $this->api_key = $api_key;
        $this->model = $model;
        $this->prompt = "";
    }

    function setPrompt($content) {
        $this->prompt = $content;
    }

    function getResponse() {
        $data = array(
            'prompt'=> $this->prompt,
            'max_tokens' => 500,
            'temperature' => 0.2,
            'model' => $this->model
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->api_key
        ));
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        // return json_decode($response);
        return json_decode($response)->choices[0]->text;
    }
}
