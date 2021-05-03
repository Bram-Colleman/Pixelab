<?php
include_once("nav.php");
include_once(__DIR__."/classes/Post.php");
include_once(__DIR__."/classes/User.php");

try {
    $user = User::fetchUserByUsername($_GET["user"]);
} catch (Exception $e) {
    $error = $e->getMessage();
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <title>Pixelab</title>
</head>
<body>
<script src="https://use.fontawesome.com/2dd2522a24.js"></script>
    <div class="container">
        <?php if (!empty($user->getAvatar())) : ?>
            <img src="./uploads/avatars/<?php echo $user->getAvatar();?>" class="rounded-circle" style="max-width: 10vw;" alt=""/>
        <?php else: ?>
            <img src="./images/blank_avatar.png" class="rounded-circle" style="max-width: 10vw;" alt=""/>
        <?php endif; ?>
    </div>




</body>
</html>
