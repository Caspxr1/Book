<?php
$servername = "localhost";
$username = "root"; // ตรวจสอบชื่อผู้ใช้
$password = ""; // ถ้ามีรหัสผ่าน ให้ใส่ตรงนี้
$database = "books";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตั้งค่ารองรับ UTF-8
$conn->set_charset("utf8");
?>
