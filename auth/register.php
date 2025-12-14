<?php
include "../config.php";

if(isset($_POST['register'])){

    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO users (username, password)
        VALUES (?,?)
    ");

    $stmt->bind_param("ss", $username, $password);

    if($stmt->execute()){
        header("Location: login.php");
        exit;
    } else {
        echo "Username sudah dipakai.";
    }
}
?>
<title>Register</title>
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
  background:#555;
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
</head>
<body>

<div class="auth-container">
  <h2>Register</h2>

  <form method="POST">

    <input type="text" name="username" placeholder="Username" required>

    <input type="password" name="password" placeholder="Password" required>

    <input type="password" name="confirm" placeholder="Confirm Password" required>

    <button type="submit" name="register">Register</button>

    <p class="switch">
      Sudah punya akun?
      <a href="login.php">Login</a>
    </p>

  </form>
</div>
