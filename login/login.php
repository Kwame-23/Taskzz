<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/register.css">
    
</head>
<body>

    <div class="container">
        <div>
            <h1 style="margin-bottom: 5%;">Login</h1>
        </div>
        <form id="login-form" class="registration-form" action="../actions/login_action.php" method="POST">   
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="password" name="password" id="password" placeholder="Password" required>

            <hr style="width: 50%; color: #000000;">
            
            <button type="submit" name="login">Login</button>
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </form>
    </div>

</body>
</html>
