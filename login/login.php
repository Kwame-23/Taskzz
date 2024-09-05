<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        *{
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #ffffff; 
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #ffffff; 
            padding: 30px; 
            border: 2px solid #32CD32; 
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
        }

        .logo {
            width: 120px;
            height: 70px;
            margin-bottom: 20px;
        }

        h1 {
            color: #020000;
            font-size: 24px;
            margin: 10px 0;
        }

        .registration-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 5px;
        }

        input[type="email"], input[type="password"] {
            width: 100%; 
            padding: 10px;
            margin-bottom: 15px;
            background-color: #f7f7f7; 
            border: none; 
            border-radius: 15px;
            outline: none;
            font-size: 16px;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #2980b9;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #3e8e41;
        }

        @media only screen and (max-width: 768px) {
            .container {
                padding: 20px;
            }
        }

        @media only screen and (max-width: 480px) {
            .container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <img src="../images/logo.jpg" alt="Logo" class="logo"> 
    <div class="container">
        <div>
            <h1 style="margin-bottom: 5%;">Hey! Sign in now</h1>
        </div>
        <form id="login-form" class="registration-form" action="../actions/login_action.php" method="POST">   
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="password" name="password" id="password" placeholder="Password" required>

            
            <button type="submit" name="login">Login</button>
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </form>
    </div>

</body>
</html>