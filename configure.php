<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meshki";

$conn = new mysqli($servername, $username, $password);

$conn->select_db("meshki");


?>
