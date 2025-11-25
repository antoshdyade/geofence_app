<?php
session_start();
$conn = new mysqli("localhost","root","","geo_fencing");

$username = $_POST['username'];
$password = md5($_POST['password']);

$q = $conn->query("SELECT * FROM admin_users WHERE username='$username' AND password='$password'");

if ($q->num_rows > 0) {
    $row = $q->fetch_assoc();

    $_SESSION['admin'] = $row['username'];
    $_SESSION['role']  = $row['role'];   // Store admin/user
    header("Location: dashboard.php");
} else {
    $_SESSION['login_error'] = "Invalid username or password";
    header("Location: login.php");
}
?>
