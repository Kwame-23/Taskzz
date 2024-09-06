<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../settings/connection.php'; // Ensure this file sets up $conn

// Check if user is logged in
$userID = $_SESSION['user_id'];

// Check if token is provided
if (!isset($_GET['token'])) {
    echo "Token is missing.";
    exit;
}

$token = $_GET['token'];

// Insert user_id and token into transactionz table
$insertQuery = "INSERT INTO transactionz (user_id, token) VALUES (?, ?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("is", $userID, $token);
$stmt->execute();

// Check if insertion was successful
if ($stmt->affected_rows <= 0) {
    echo "Failed to insert transaction.";
    exit;
}

// Close the prepared statement
$stmt->close();

// Set up the cURL request to query the transaction status
$url = "https://sandbox.expresspaygh.com/api/query.php";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, 1);

$requestBody = "merchant-id=564265612872&api-key=06K1VG9VpSdY3hfqjgNVF-QkLlGadW7tlUvdT53iAB-7ikCeyoT8bLrsu6WKogt-BIjRAF4TRJHVRihjA9m&token=$token";
curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($httpCode !== 200) {
    echo "Error in the transaction query.";
    exit;
}

// Decode the response
$data = json_decode($response);

$status = $data->result;
$date = $data->{'date-processed'};
$currency = $data->currency;
$amount = $data->amount;
$transactionID = $data->{'transaction-id'};

// Check the transaction status
if ($status == 1) {
    // Transaction successful, update the user's premium status
    $updateQuery = "UPDATE users SET premium = TRUE WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $userID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Transaction Successful";
        echo "Transaction, with ID $transactionID of $currency $amount has been done successfully";
        echo "Date Processed: $date";
    } else {
        echo "Failed to update user premium status.";
    }

    // Redirect to mainpage.php
    header("Location: ../view/mainpage.php");
    exit;

} elseif ($status == 4) {
    echo "Pending...";
} else {
    echo "Transaction Failed";
}
?>
