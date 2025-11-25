<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["status"=>"error", "message"=>"Unauthorized"]);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(["status"=>"error", "message"=>"Missing ID"]);
    exit;
}

$id = intval($_GET['id']);

$conn = new mysqli("localhost", "root", "", "geo_fencing");

// Prevent deleting yourself
$usernameRes = $conn->query("SELECT username FROM admin_users WHERE id=$id");
$row = $usernameRes->fetch_assoc();

if ($row['username'] === $_SESSION['admin']) {
    echo json_encode(["status"=>"error", "message"=>"You cannot delete your own admin account!"]);
    exit;
}

// Perform deletion
$q = $conn->query("DELETE FROM admin_users WHERE id=$id");

if ($q) {
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error", "message"=>"Database error"]);
}
?>
