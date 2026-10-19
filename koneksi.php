<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "3gameku";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Koneksi gagal: " . $conn->connect_error]));
}

$conn->set_charset("utf8mb4");
?>