<?php
$servername = "localhost";
$username = "";
$password = "";
$conn = new mysqli($servername, $username, $password);
$conn->set_charset("utf8");
$conn->select_db("parser");
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
set_time_limit(5 * 60);
