<?php

session_start();

function checkLogin() {
    // Check if user_id session exists
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login/login.php');    
        die();
    }
}
