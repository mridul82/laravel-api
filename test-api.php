<?php

// Login to get a token
$loginData = [
    'email' => 'admin@example.com',
    'password' => 'password'
];

$ch = curl_init('http://localhost:8000/api/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "Login failed with HTTP code: $httpCode\n";
    echo "Response: $response\n";
    exit(1);
}

$responseData = json_decode($response, true);
$token = $responseData['data']['token'];

echo "Login successful. Token: $token\n\n";

// Now use the token to access the users endpoint
$ch = curl_init('http://localhost:8000/api/users?page=1&per_page=10');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Users API response code: $httpCode\n";
if ($httpCode === 200) {
    $data = json_decode($response, true);
    echo "Number of users: " . count($data['data']['users']) . "\n";
    echo "Total users: " . $data['data']['pagination']['total'] . "\n";
} else {
    echo "Response: $response\n";
}
