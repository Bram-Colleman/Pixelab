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
    <title>Upload Post</title>
</head>
<body class="login-body">
<div class="background-overlay"></div>
<div class="flexbox">
    <div class="login-card">
        <!--        <form method="post" action class="login-form">-->
        <!--            <input name="email" value="email@email.com" type="email" required autofocus class="login-field"/>-->
        <!--            <input name="username" value="Username" type="text" required autofocus class="login-field"/>-->
        <!--            <input name="bio" value="bio" type="text" required autofocus class="login-field"/>-->
        <!--            <input name="oldPassword" placeholder="oldPassword" type="password" required class="login-field"/>-->
        <!--            <input name="newPassword" placeholder="newPassword" type="password" required class="login-field"/>-->
        <!--            <input name="login" type="submit" value="Log in" class="login-button"/>-->
        <!--        </form>-->
        <div style="width: 75%;  margin-left: 12.5%; margin-top: 10%" class="row">
            <div class="col">
                <img class="avatar" src="./images/blank_avatar.png" alt="avatar">
            </div>
            <div class="col">
            <form >
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="inputDescription" class="form-label">Description</label>
                            <input type="text" class="form-control" style="height: 15rem;" id="inputDescription" name="description">
                        </div>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            </div>

        </div>

    </div>

</div>

</body>
</html>
