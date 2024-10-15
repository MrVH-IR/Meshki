<?php

$SER_ADD = "localhost";
$SER_USER = "root";
$SER_PASS = "";
$DB_NAME = "meshki";

function connectToDatabase() {
    global $SER_ADD, $SER_USER, $DB_NAME, $SER_PASS;
    try {
        return new PDO("mysql:host=$SER_ADD;dbname=$DB_NAME", $SER_USER, $SER_PASS);
    } catch (PDOException $e) {
        die("خطا در اتصال به پایگاه داده: " . $e->getMessage());
    }
}
?>
