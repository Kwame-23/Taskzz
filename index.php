<!DOCTYPE html>
<html>
<head>
    <title>ListaVista</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .container {
            display: grid;
            grid-template-columns: 40% 60%;
            height: 100vh;
        }
        
        .left-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: white;
            margin-left:15%;
            margin-right: 20%;
            text-align: center;
            font-size: 36px;

        }
        
        .left-panel img {
            width: 50%;
            margin-bottom: 20px;
        }
        
        .left-panel button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
        }
        
        .left-panel button:hover {
            background-color: #3e8e41;
        }
        
        .right-panel {
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <img src="images/logo.jpg" alt="Logo">
            <p>Welcome to ListaVista!<br> Let's turn your notes into a reality</p>
            <button onclick="location.href='login/login.php'">Let's Do This!</button>
        </div>
        <div class="right-panel" style="background-image: url('images/index_design.jpg');"></div>
    </div>
</body>
</html>