<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "geo_fencing");

if (!isset($_GET['id'])) {
    echo json_encode(["status" => "error", "message" => "Missing ID"]);
    exit;
}

$id = intval($_GET['id']);

$q = $conn->query("DELETE FROM geofences WHERE id = $id");

if ($q) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "SQL error"]);
}
?>
