<?php
session_start();

// Only admin can access this page
if (!isset($_SESSION['admin']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$conn = new mysqli("localhost","root","","geo_fencing");

$message = "";

// CREATE NEW USER
if ($_SERVER['REQUEST_METHOD']=="POST") {
    $u = trim($_POST['username']);
    $p = trim($_POST['password']);
    $role = "user"; // New users default to normal user role

    if ($u === "" || $p === "") {
        $message = "Fields cannot be empty!";
    } else {
        $hashed = md5($p);
        $exists = $conn->query("SELECT * FROM admin_users WHERE username='$u'");
        
        if ($exists->num_rows > 0) {
            $message = "Username already exists!";
        } else {
            $conn->query("INSERT INTO admin_users(username,password,role) 
                          VALUES('$u','$hashed','$role')");
            $message = "User created successfully!";
        }
    }
}

// FETCH ALL USERS
$users = $conn->query("SELECT id, username, role, created_at FROM admin_users");
?>
<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>
<style>
body { font-family: Arial; margin: 20px; }
button { padding: 8px 14px; cursor: pointer; }
.message { color: green; font-weight: bold; }
.error { color: red; font-weight: bold; }

.delete-btn {
    padding:5px 10px;
    background:#e74c3c;
    color:white;
    border:none;
    border-radius:4px;
    cursor:pointer;
}

input[type=text], input[type=password] {
    padding:8px;
    width:200px;
    margin-bottom:10px;
}

table {
    border-collapse: collapse;
    margin-top: 15px;
}
table th, table td {
    border:1px solid #333;
    padding:8px 15px;
}
</style>
</head>
<body>

<h2>Manage Users</h2>
<a href="dashboard.php"><button>⬅ Back to Dashboard</button></a>
<br><br>

<?php if ($message) echo "<p class='message'>$message</p>"; ?>

<!-- Add New User Form -->
<form method="POST">
  <label>Username:</label><br>
  <input type="text" name="username" required><br>

  <label>Password:</label><br>
  <input type="password" name="password" required><br>

  <button type="submit" style="background:#3498db; color:white;">Create User</button>
</form>

<br><h3>Existing Users</h3>

<table>
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Role</th>
    <th>Created At</th>
    <th>Action</th>
</tr>

<?php while($u = $users->fetch_assoc()) { ?>
<tr>
    <td><?= $u['id'] ?></td>
    <td><?= $u['username'] ?></td>
    <td><?= $u['role'] ?></td>
    <td><?= $u['created_at'] ?></td>

    <td>
        <?php if ($u['username'] != $_SESSION['admin']) { ?>
            <button class="delete-btn" onclick="deleteUser(<?= $u['id'] ?>)">🗑 Delete</button>
        <?php } else { ?>
            <span style="color:gray;">(Owner)</span>
        <?php } ?>
    </td>
</tr>
<?php } ?>

</table>

<script>
function deleteUser(id) {
    if (!confirm("Are you sure you want to delete this user?")) return;

    fetch("delete_user.php?id=" + id, { method: "GET" })
    .then(r => r.json())
    .then(res => {
        if (res.status === "success") {
            alert("User deleted successfully!");
            location.reload();
        } else {
            alert("Error: " + res.message);
        }
    });
}
</script>

</body>
</html>
