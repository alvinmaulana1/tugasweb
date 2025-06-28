<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = md5($_POST['password']); // Sesuai dengan yang disimpan

    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$user' AND password='$pass'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $_SESSION['admin'] = $user;
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Username atau Password salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <style>
        body {
            font-family: sans-serif;
            background: #ecf0f1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
            width: 300px;
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
        }
        button:hover {
            background: #27ae60;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login Admin</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username admin" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">LOGIN</button>
    </form>
</div>

</body>
</html>
