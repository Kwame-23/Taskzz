<?php

include("../settings/connection.php");


$userID = intval($_GET['user_id']); // Get user_id from URL
$sql = "SELECT firstname, lastname, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User found, fetch details
    $user = $result->fetch_assoc();
    $firstname = $user['firstname'];
    $lastname = $user['lastname'];
    $email = $user['email'];
    $username = $email; // Username is the same as the email
} else {
    die("User not found.");
}

$stmt->close();
$conn->close();

// Generate a random 10-digit phone number starting with '0'
$phonenumber = '0' . str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT);

// Generate a random 16-character order ID
$orderID = bin2hex(random_bytes(8));

// Set the payment parameters
$url = "https://sandbox.expresspaygh.com/api/submit.php";
$merchantID = "564265612872";
$apiKey = "06K1VG9VpSdY3hfqjgNVF-QkLlGadW7tlUvdT53iAB-7ikCeyoT8bLrsu6WKogt-BIjRAF4TRJHVRihjA9m";
$currency = "GHS";
$amount = 50; // Set amount to 50
$redirectURL = "http://13.49.18.226/actions/confirmpayment.php"; // Redirect URL to Google

// Set up cURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, 1);

// Set the request body in x-www-form-urlencoded format
$requestBody = http_build_query([
    'currency' => $currency,
    'merchant-id' => $merchantID,
    'api-key' => $apiKey,
    'firstname' => $firstname,
    'lastname' => $lastname,
    'email' => $email,
    'phonenumber' => "0553371515",
    'amount' => $amount,
    'order-id' => $orderID,
    'redirect-url' => $redirectURL,
    
]);

curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($httpCode === 200) {
    $data = json_decode($response);
    $token = $data->token;
    // var_dump($data);exit;
    $orderID = $data->{'order-id'};

    // Redirect to the expressPay checkout page with the token
    header("Location: https://sandbox.expresspaygh.com/api/checkout.php?token=$token");
    exit;
} else {
    echo "Error processing payment. HTTP Code: $httpCode";
}
?>