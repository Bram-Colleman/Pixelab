<?php
include_once("nav.php");
include_once (__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Uploader.php");

if (!empty($_POST)) {
    if(!isset($_SESSION)) {
        session_start();
    }
    $uploader = new Uploader($_SESSION['user']);
    $uploader->uploadAvatar();
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
    <link rel="stylesheet" href="styles/style.css">
    <title>Upload</title>
</head>
<body>
<div class="text-center">
    <?php if ($uploader->getUploadOk() == 0) : ?>
        <img class="icon" src="images/confirmation_icon.svg" alt="confirmation icon">
        <p>Your profile settings have been changed!</p>
        <p class="lead">
            <a class="btn btn-primary btn-sm" href="profile.php" role="button">Back to profile settings</a>
        </p>
    <?php endif; ?>

    <?php if ($uploader->getUploadOk() == 1) : ?>
        <img class="icon" src="images/error_icon.svg" alt="confirmation icon">
        <p>File is not an image!</p>
        <p class="lead">
            <a class="btn btn-primary btn-sm" href="profile.php" role="button">Back to profile settings</a>
        </p>
    <?php endif; ?>

    <?php if ($uploader->getUploadOk() == 2) : ?>
        <img class="icon" src="images/error_icon.svg" alt="confirmation icon">
        <p>File is too large like my cock!</p>
        <p class="lead">
            <a class="btn btn-primary btn-sm" href="profile.php" role="button">Back to profile settings</a>
        </p>
    <?php endif; ?>

    <?php if ($uploader->getUploadOk() == 3) : ?>
        <img class="icon" src="images/error_icon.svg" alt="confirmation icon">
        <p>Only JPG, JPEG or PNG files are allowed!</p>
        <p class="lead">
            <a class="btn btn-primary btn-sm" href="profile.php" role="button">Back to profile settings</a>
        </p>
    <?php endif; ?>

</div>
</body>
</html>
