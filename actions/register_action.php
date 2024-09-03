<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("../settings/connection.php");

if (isset($_POST['register'])) {
    $fname = $_POST['firstName'];
    $lname = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];

    // Check if passwords match
    if ($password != $confirm_password) {
        header("Location: ../login/register.php?msg=Passwords do not match!");
        exit;
    }

    // Check if the email is already registered
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: ../login/register.php?msg=Email Already Registered!");
        exit;
    }

    // Hash the password
    $hashpassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $stmt = $conn->prepare("INSERT INTO Users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fname, $lname, $email, $hashpassword);

    if ($stmt->execute()) {
        header("Location: ../login/login.php?msg=Email Registered Successfully!");
        exit;
    } else {
        echo "Error: " . $conn->error;
        exit;
    }
}
?>
