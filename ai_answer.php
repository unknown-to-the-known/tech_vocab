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

$result = json_decode($response, true);

echo $result['choices'][0]['message']['content'];

?>