<?php
ob_start();
header("Content-Type: application/json");
require_once "koneksi.php";

$input = json_decode(file_get_contents("php://input"), true);

$nama = trim($input["nama"] ?? "");
$badge = trim($input["badge"] ?? "");
$level = (int)($input["level"] ?? 1);
$hitung = (int)($input["hitung"] ?? 0);
$buah = (int)($input["buah"] ?? 0);
$tic = (int)($input["tic"] ?? 0);
$xp = (int)($input["xp"] ?? 0);
$skor = $hitung + $buah + $tic;

if (!$nama || !$badge) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Nama dan Badge wajib diisi"]);
    exit;
}

$cek = $conn->prepare("SELECT id FROM pemain WHERE badge = ?");
$cek->bind_param("s", $badge);
$cek->execute();
$cek->store_result();

if ($cek->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE pemain SET nama=?, level=?, hitung=?, buah=?, tic=?, skor=?, xp=? WHERE badge=?");
    $stmt->bind_param("siiiiiss", $nama, $level, $hitung, $buah, $tic, $skor, $xp, $badge);
} else {
    $stmt = $conn->prepare("INSERT INTO pemain (nama, badge, level, hitung, buah, tic, skor, xp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiiiii", $nama, $badge, $level, $hitung, $buah, $tic, $skor, $xp);
}

if ($stmt->execute()) {
    $res = ["success" => true, "message" => "Data tersimpan"];
} else {
    $res = ["success" => false, "message" => "Gagal simpan: " . $conn->error];
}

$cek->close();
$stmt->close();
$conn->close();

ob_end_clean();
echo json_encode($res);
exit;