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
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Sign Up</title>
</head>
<body class="login-body">
<img src="./images/pixelab_logo.png" alt="" class="w-20-vw h-centered mt-5-rem">
<form method="post" class="h-centered v-centered translate-50-50 mt-5">
    <label for="email" class="form-label fw-bold pt-5">Email</label>
    <input id="email" name="email" placeholder="Email" type="email" required autofocus class="form-control"/>

    <label for="username" class="form-label fw-bold pt-2">Username</label>
    <input id="username" name="username" placeholder="Username" type="text" required class="form-control"/>

    <label for="password" class="form-label fw-bold pt-2">Password</label>
    <input id="password" name="password" placeholder="Password" type="password" required class="form-control"/>

    <input name="register" type="submit" value="Register" class="btn btn-primary mt-2"/>
    <p class="pt-2">Already have an account? <a href="login.php">Login.</a></p>
</form>

</body>
</html>