<?php
session_start();
include("../settings/connection.php");


if (isset($_POST['addTask'])) {
    $taskName = trim($_POST['taskName']);
    $description = trim($_POST['description']);
    $id=1;
    $list_id=2;

    



}
