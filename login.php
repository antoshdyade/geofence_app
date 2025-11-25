<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Login – GeoFence System</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #e4e9f7;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.container {
    width: 350px;
    background: white;
    padding: 25px;
    border-radius: 6px;
    box-shadow: 0px 0px 12px rgba(0,0,0,0.15);
}
h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #2c3e50;
}
input[type=text], input[type=password] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #aaa;
    border-radius: 4px;
}
button {
    width: 100%;
    padding: 10px;
    background: #1976d2;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}
button:hover { background: #125a9c; }
.error { color: red; text-align: center; }
</style>
</head>
<body>
<div class="container">
  <h2>Admin Login</h2>

  <?php if(isset($_SESSION['login_error'])) { ?>
     <p class="error"><?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>
  <?php } ?>

  <form action="login_check.php" method="POST">
      <input type="text" name="username" placeholder="Enter Username" required>
      <input type="password" name="password" placeholder="Enter Password" required>
      <button type="submit">Login</button>
  </form>
</div>
</body>
</html>
