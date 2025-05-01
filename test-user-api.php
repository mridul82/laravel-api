<?php

// Configuration
$baseUrl = 'http://localhost:8000/api';
$adminEmail = 'admin@example.com';
$adminPassword = 'password';

echo "=== Testing User Management API ===\n\n";

// Step 1: Login to get a token
echo "Step 1: Logging in as admin user...\n";
$loginData = [
    'email' => $adminEmail,
    'password' => $adminPassword
];

$ch = curl_init("$baseUrl/login");
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
    echo "❌ Login failed with HTTP code: $httpCode\n";
    echo "Response: $response\n";
    exit(1);
}

$responseData = json_decode($response, true);
$token = $responseData['data']['token'];

echo "✅ Login successful. Token received.\n\n";

// Step 2: Get users list
echo "Step 2: Fetching users list...\n";
$ch = curl_init("$baseUrl/users?page=1&per_page=10");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "❌ Failed to fetch users with HTTP code: $httpCode\n";
    echo "Response: $response\n";
    exit(1);
}

$data = json_decode($response, true);
$users = $data['data']['users'];
$pagination = $data['data']['pagination'];

echo "✅ Successfully fetched users list.\n";
echo "   Total users: {$pagination['total']}\n";
echo "   Current page: {$pagination['current_page']} of {$pagination['last_page']}\n";
echo "   Users per page: {$pagination['per_page']}\n\n";

// Step 3: Create a new user
echo "Step 3: Creating a new user...\n";
$newUser = [
    'name' => 'Test User ' . time(),
    'email' => 'testuser' . time() . '@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'roles' => ['User']
];

$ch = curl_init("$baseUrl/users");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($newUser));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 201 && $httpCode !== 200) {
    echo "❌ Failed to create user with HTTP code: $httpCode\n";
    echo "Response: $response\n";
    exit(1);
}

$createdUser = json_decode($response, true);
$userId = $createdUser['data']['id'] ?? $createdUser['data']['user']['id'] ?? null;

if (!$userId) {
    echo "❌ Created user, but couldn't extract user ID from response.\n";
    echo "Response: " . json_encode($createdUser, JSON_PRETTY_PRINT) . "\n";
    exit(1);
}

echo "✅ Successfully created new user with ID: $userId\n\n";

// Step 4: Get user details
echo "Step 4: Fetching user details...\n";
$ch = curl_init("$baseUrl/users/$userId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "❌ Failed to fetch user details with HTTP code: $httpCode\n";
    echo "Response: $response\n";
    exit(1);
}

$userData = json_decode($response, true);
echo "✅ Successfully fetched user details.\n";
echo "   Name: {$userData['data']['name']}\n";
echo "   Email: {$userData['data']['email']}\n\n";

// Step 5: Update user
echo "Step 5: Updating user...\n";
$updateData = [
    'name' => 'Updated Test User',
    'email' => $userData['data']['email'],
    'password' => 'newpassword123',
    'password_confirmation' => 'newpassword123',
    'roles' => ['User']
];

$ch = curl_init("$baseUrl/users/$userId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updateData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "❌ Failed to update user with HTTP code: $httpCode\n";
    echo "Response: $response\n";
    exit(1);
}

echo "✅ Successfully updated user.\n\n";

// Step 6: Delete user
echo "Step 6: Deleting user...\n";
$ch = curl_init("$baseUrl/users/$userId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "❌ Failed to delete user with HTTP code: $httpCode\n";
    echo "Response: $response\n";
    exit(1);
}

echo "✅ Successfully deleted user.\n\n";

// Step 7: Get roles
echo "Step 7: Fetching roles...\n";
$ch = curl_init("$baseUrl/roles");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "❌ Failed to fetch roles with HTTP code: $httpCode\n";
    echo "Response: $response\n";
    exit(1);
}

$rolesData = json_decode($response, true);
$roles = $rolesData['data'];

echo "✅ Successfully fetched roles.\n";
echo "   Available roles: " . implode(', ', array_column($roles, 'name')) . "\n\n";

echo "=== All tests passed successfully! ===\n";
echo "The admin user now has full access to the user management API.\n";
