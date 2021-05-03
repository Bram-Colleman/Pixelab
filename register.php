<?php

include_once(__DIR__ . '/classes/User.php');

session_start();
session_destroy();

if (!empty($_POST)) {
    try {
        $user = new User(null, $_POST['username'], $_POST['email'], null, null, $_POST['password']);
        $user->register();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
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
<img src="./images/pixelab_logo.png" alt="" class="login-logo">
<form method="post" class="login-form">
    <input name="email" placeholder="Email" type="email" required autofocus class="login-field"/>
    <input name="username" placeholder="Username" type="text" required class="login-field"/>
    <input name="password" placeholder="Password" type="password" required class="login-field"/>
    <input name="register" type="submit" value="Register" class="login-button"/>
</form>
<p>Already have an account? <a href="login.php" class="login-link">Login.</a></p>
</div>

</body>
</html>