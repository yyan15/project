<?php
session_start();
include "../config.php";

$error = "";

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("
        SELECT user_id, username, password
        FROM users
        WHERE username = ?
        LIMIT 1
    ");

    $stmt->bind_param("s",$username);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows === 1){

        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id']   = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            header("Location: ../task/list_task.php");
            exit;
        }
    }

    $error = "Username atau Password salah";
}
?>

<title>Login</title>

<style>
*{
  box-sizing:border-box;
  font-family: "Patrick Hand", cursive;
}

body{
  height:100vh;
  background: linear-gradient(135deg,#566369,#1b1f21);
  margin:0;
  display:flex;
  align-items:center;
  justify-content:center;
}

.auth-container{
  background:white;
  width:320px;
  padding:30px;
  border-radius:12px;
  text-align:center;
  box-shadow:0 10px 30px rgba(0,0,0,0.15);
}

.auth-container h2{
  margin-bottom:20px;
  color:#444;
}

input{
  width:100%;
  padding:10px;
  margin:8px 0;
  border-radius:6px;
  border:1px solid #ccc;
  outline:none;
}

input:focus{
  border-color:#555;
}

button{
  width:100%;
  padding:12px;
  background:#555;
  border:none;
  color:white;
  border-radius:6px;
  cursor:pointer;
  margin-top:10px;
  font-weight:bold;
}

button:hover{
  background:#333;
}

.error-msg{
    margin-top:8px;
    color:#e53935;
    font-size:14px;
}

.switch{
  margin-top:12px;
  font-size:13px;
}

.switch a{
  color:#555;
  font-weight:bold;
  text-decoration:none;
}
</style>

<div class="auth-container">
  <h2>Login</h2>

  <form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>

    <button type="submit" name="login">Login</button>

    <!-- ERROR MESSAGE -->
    <?php if(!empty($error)): ?>
        <div class="error-msg"><?= $error ?></div>
    <?php endif; ?>

    <p class="switch">
      Belum punya akun?
      <a href="register.php">Register</a>
    </p>
  </form>
</div>
