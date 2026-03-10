<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$api_key = "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";

$question = $_POST['question'];

$data = [
"model" => "gpt-4o-mini",
"messages" => [
[
"role" => "system",
"content" => "You are a friendly tutor for grade 3 to grade 10 students. Explain answers simply."
],
[
"role" => "user",
"content" => $question
]
]
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
"Content-Type: application/json",
"Authorization: Bearer " . $api_key
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

curl_close($ch);

// $result = json_decode($response, true);

// echo $result['choices'][0]['message']['content'];









$result = json_decode($response, true);

if(isset($result['error'])){
    echo "API Error: " . $result['error']['message'];
    exit;
}

if(!isset($result['choices'][0]['message']['content'])){
    echo "Unexpected response:";
    print_r($result);
    exit;
}

echo $result['choices'][0]['message']['content'];

?>