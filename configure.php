<?php
// ایجاد اتصال به پایگاه داده
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meshki";

// // // ایجاد اتصال
$conn = new mysqli($servername, $username, $password);

// // // بررسی اتصال
// if ($conn->connect_error) {
//     die("خطا در اتصال به پایگاه داده: " . $conn->connect_error);
// }

// ایجاد پایگاه داده
// $sql = "CREATE DATABASE IF NOT EXISTS meshki";
// if ($conn->query($sql) === TRUE) {
//     echo "پایگاه داده meshki با موفقیت ایجاد شد<br>";
// } else {
//     echo "خطا در ایجاد پایگاه داده: " . $conn->error . "<br>";
// }

// // انتخاب پایگاه داده
$conn->select_db("meshki");
// // ایجاد جدول‌ها
// $tables = array(
//     "tblusers" => "CREATE TABLE tblusers (
//         id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//         username VARCHAR(30) NOT NULL,
//         email VARCHAR(50),
//         reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
//     )",
//     "tbladmins" => "CREATE TABLE tbladmins (
//         id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//         username VARCHAR(30) NOT NULL,
//         password VARCHAR(255) NOT NULL,
//         phone VARCHAR(11),
//         email VARCHAR(50)
//     )",
//     "tblsongs" => "CREATE TABLE tblsongs (
//         id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//         title VARCHAR(100) NOT NULL,
//         artist VARCHAR(50),
//         album VARCHAR(50),
//         release_date DATE
//     )",
//     "tblvids" => "CREATE TABLE tblvids (
//         id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//         title VARCHAR(100) NOT NULL,
//         description TEXT,
//         upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
//     )",
//     "tbllogs" => "CREATE TABLE tbllogs (
//         id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//         user_id INT(6) UNSIGNED,
//         action VARCHAR(255) NOT NULL,
//         log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
//     )"
// );

// foreach ($tables as $table_name => $sql) {
//     if ($conn->query($sql) === TRUE) {
//         echo "جدول $table_name با موفقیت ایجاد شد<br>";
//     } else {
//         echo "خطا در ایجاد جدول $table_name: " . $conn->error . "<br>";
//     }
// }

// بستن اتصال
// $alter_tblusers = "ALTER TABLE tblusers ADD COLUMN password VARCHAR(255) NOT NULL AFTER email";

// if ($conn->query($alter_tblusers) === TRUE) {
//     echo "ستون password با موفقیت به جدول tblusers اضافه شد<br>";
// } else {
//     echo "خطا در اضافه کردن ستون password به جدول tblusers: " . $conn->error . "<br>";

// حذف جدول tblsongs قبلی
// $drop_tblsongs = "DROP TABLE IF EXISTS tblsongs";
// if ($conn->query($drop_tblsongs) === TRUE) {
//     echo "جدول tblsongs قبلی با موفقیت حذف شد<br>";
// } else {
//     echo "خطا در حذف جدول tblsongs قبلی: " . $conn->error . "<br>";
// }

// // ایجاد جدول tblsongs جدید
// $create_tblsongs = "CREATE TABLE tblsongs (
//     id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     artist VARCHAR(50) NOT NULL,
//     songName VARCHAR(100) NOT NULL,
//     songPath VARCHAR(255) NOT NULL,
//     posterPath VARCHAR(255) NOT NULL,
//     description TEXT,
//     tags VARCHAR(255)
// )";

// if ($conn->query($create_tblsongs) === TRUE) {
//     echo "جدول tblsongs جدید با موفقیت ایجاد شد<br>";
// } else {
//     echo "خطا در ایجاد جدول tblsongs جدید: " . $conn->error . "<br>";
// }


// $conn->close();
// اضافه کردن ستون password به جدول tblusers
// }

?>
