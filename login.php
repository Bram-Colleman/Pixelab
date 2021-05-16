<?php
include_once(__DIR__ . "/classes/User.php");

session_start();
session_destroy();

if (!empty($_POST)) {
    try {
        User::login($_POST['email'], $_POST['password']);
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
    <link rel="stylesheet" href="styles/bootstrap.min.css">
        <link rel="stylesheet" href="./styles/style.css">
    <title>Login</title>
</head>
<body>
<?php if (isset($error)): ?>
    <div class="alert alert-danger text-center">
        <p><?php echo $error ?></p>
    </div>
<?php endif; ?>
<div class="flexbox ">
    <img src="./images/pixelab_logo.png" alt="" class="w-20-vw h-centered mt-5-rem">
    <div class="container w-25">
        <form method="post" class="h-centered v-centered translate-50-50 mt-5">
            <label for="email" class="form-label fw-bold pt-5">Email</label>
            <input id="email" name="email" placeholder="Email" type="email" required autofocus class="form-control"/>

            <label for="password" class="form-label fw-bold pt-2">Password</label>
            <input id="password" name="password" placeholder="Password" type="password" required class="form-control"/>

            <input name="login" type="submit" value="Log in" class="btn btn-primary mt-2"/>
            <p class="pt-2">Don't have an account? <a href="register.php">Register.</a></p>

        </form>
    </div>

</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>
