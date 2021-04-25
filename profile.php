<?php
include_once("nav.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--    <link rel="stylesheet" href="./styles/style.css">-->
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <title>Profile</title>
</head>
<body class="login-body">
<div class="background-overlay"></div>
<div class="flexbox">
    <img class="avatar rounded-circle" src="./images/blank_avatar.png" alt="avatar">
    <div class="login-card">
<!--        <form method="post" action class="login-form">-->
<!--            <input name="email" value="email@email.com" type="email" required autofocus class="login-field"/>-->
<!--            <input name="username" value="Username" type="text" required autofocus class="login-field"/>-->
<!--            <input name="bio" value="bio" type="text" required autofocus class="login-field"/>-->
<!--            <input name="oldPassword" placeholder="oldPassword" type="password" required class="login-field"/>-->
<!--            <input name="newPassword" placeholder="newPassword" type="password" required class="login-field"/>-->
<!--            <input name="login" type="submit" value="Log in" class="login-button"/>-->
<!--        </form>-->
        <div style="width: 50%; padding-left: 10%">

        <form >
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="inputUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="inputUsername" value="Test" name="username">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="inputBio" class="form-label">Bio</label>
                        <input type="text" class="form-control" id="inputBio" value="Bio" name="bio">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="inputEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="inputEmail" value="test@test.be" name="email">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="inputOldPassword" class="form-label">Old Password</label>
                        <input type="password" class="form-control" id="inputOldPassword" name="oldPassword">
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="mb-3">
                        <label for="inputNewPassword" class="form-label">Old Password</label>
                        <input type="password" class="form-control" id="inputNewPassword" name="newPassword">
                    </div>
                </div>

        </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        </div>

    </div>

</div>

</body>
</html>
