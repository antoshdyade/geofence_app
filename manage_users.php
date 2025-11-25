<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$conn = new mysqli("localhost","root","","geo_fencing");
$message = "";

if ($_SERVER['REQUEST_METHOD']=="POST") {
    $u = trim($_POST['username']);
    $p = trim($_POST['password']);
    $role = "user"; // New users are normal users

    if ($u === "" || $p === "") {
        $message = "Fields cannot be empty!";
    } else {
        $hashed = md5($p);
        $exists = $conn->query("SELECT * FROM admin_users WHERE username='$u'");
        if ($exists->num_rows > 0) {
            $message = "Username already exists!";
        } else {
            $conn->query("INSERT INTO admin_users(username,password,role) VALUES('$u','$hashed','$role')");
            $message = "User created successfully.";
        }
    }
}

$users = $conn->query("SELECT id, username, role, created_at FROM admin_users");
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>
<style>
body { font-family: Arial; margin: 20px; }
button { padding: 8px 14px; cursor: pointer; }
.message { color: green; }
.error { color: red; }
</style>
</head>
<body>

<h2>Manage Users</h2>
<a href="dashboard.php"><button>⬅ Back to Dashboard</button></a>
<br><br>

<?php if ($message) echo "<p class='message'>$message</p>"; ?>

<form method="POST">
  Username: <input type="text" name="username" required>
  Password: <input type="password" name="password" required>
  <button type="submit" style="background:#3498db; color:white;">Create User</button>
</form>

<br><h3>Existing Users</h3>
<table border="1" cellspacing="0" cellpadding="8">
<tr><th>ID</th><th>Username</th><th>Role</th><th>Created At</th></tr>
<?php while($u = $users->fetch_assoc()) { ?>
<tr>
<td><?= $u['id'] ?></td>
<td><?= $u['username'] ?></td>
<td><?= $u['role'] ?></td>
<td><?= $u['created_at'] ?></td>
</tr>
<?php } ?>
</table>

</body>
</html>
