<?php
session_start();  

include("../settings/connection.php");

if (isset($_POST["login"])) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $passwd = $_POST["password"];

    // Check if email and password are filled
    if (empty($email) || empty($passwd)) {
        header("Location: ../login/login.php?msg=Please fill in all required fields.");
        exit();
    }

    // Query to check if the user exists
    $sql_query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($passwd, $row['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
           // $_SESSION['firstName'] = $row['firstName']; // Store user's first name in session

            header("Location: ../view/mainpage.php");
            exit();
        } else {
            header("Location: ../login/login.php?msg=Invalid email or password.");
            exit();
        }
    } else {
        header("Location: ../login/login.php?msg=Invalid email or password.");
        exit();
    }
} else {
    header("Location: ../login/login.php?msg=An error occurred. Please try again.");
    die();
}
?>
