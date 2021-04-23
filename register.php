<?php 

include_once (__DIR__ . '/classes/Db.php');

$conn = Db::getConnection();

    if (!empty($_POST)){
        $email = $_POST['email'];
        $userName = $_POST['userName'];
        $options = [
            'cost' => 12, 
        ];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT, $options);
        //echo $password; 

        // $conn = new mysqli("localhost", "root", "root", "DigitalDime");
        $result = $conn->query("INSERT INTO Users (email, userName, password) VALUES ('".$conn->real_escape_string($email)."', '".$conn->real_escape_string($userName)."', '".$conn->real_escape_string($password)."')");
        //var_dump($result);
    }

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Sign Up</title>
</head>
<body class="login-body">
<div class="background-overlay"></div>
<div class="flexbox">
    <div class="login-card">
        <img src="./images/Logo.png" alt="" class="login-logo">
        <form method="post" action class="login-form">
            <input name="email" placeholder="Email" type="email" required autofocus class="login-field"/>
            <input name="userName" placeholder="user name" type="userName" required class="login-field"/>
            <input name="password" placeholder="Password" type="password" required class="login-field"/>
            <input name="register" type="submit" value="Register" class="login-button"/>
        </form>
    </div>
    <div class="login-card">
        <p>Already have an account? <a href="#" class="login-link">Login.</a></p>
    </div>
</div>

</body>
</html>