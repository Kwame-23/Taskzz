<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>

    <div class="container">
        <div>
            <h1>Sign Up</h1>
            <hr style="width: 50%; color: #000000;">
            <hr style="width: 63%; margin-bottom: 5%; margin-top: 2%;">
        </div>
        <form id="register-form" class="registration-form" action="../actions/register_action.php" method="POST" required> 
            <input type="text" name="firstName" id="firstName" placeholder="First Name" required>
            <input type="text" name="lastName" id="lastName" placeholder="Last Name" required>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>
            <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Re-enter your password" required>

            <hr style="width: 63%; margin-top: 2%;">
            <hr style="width: 50%; color: #000000;">
            
            <button type="submit" name="register">Register</button>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>

</body>
</html>
