<?php
include_once(__DIR__ . "/classes/User.php");

if (!empty($_POST)) {
    try {
        $user = new User();
        $user->login($_POST['email'], $_POST['password']);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }

}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--    <link rel="stylesheet" href="./styles/style.css">-->
    <title>Login</title>
</head>
<body class="login-body">
<div class="background-overlay"></div>
<div class="flexbox">
    <div class="login-card">
        <!--<img src="./images/Logo.png" alt="" class="login-logo">-->
        <?php if (isset($error)): ?>
            <p><?php echo $error ?></p>
        <?php endif; ?>
        <form method="post" class="login-form">
            <input name="email" placeholder="Email" type="email" required autofocus class="login-field"/>
            <input name="password" placeholder="Password" type="password" required class="login-field"/>
            <input name="login" type="submit" value="Log in" class="login-button"/>
        </form>
    </div>
    <div class="login-card">
        <p>Don't have an account? <a href="#" class="register-link">Register.</a></p>
    </div>
</div>

</body>
</html>
