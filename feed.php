<?php
include_once("nav.php");

$posts = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
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
    <title>Pixelab</title>
</head>
<body>
<script src="https://use.fontawesome.com/2dd2522a24.js"></script>
<?php foreach ($posts as $post): ?>
    <div class="container-fluid shadow-sm"
         style="width: 35%; padding-top: 1rem; padding-bottom: 1rem; margin-top: 1.5rem;">
<!--    username and avatar:-->
        <div class="row" style="height: 5%;">
            <div class="col-1" style="align-self: center">
                <img src="./images/blank_avatar.png" alt="" class="rounded-circle" style="height: 75%">
            </div>
            <div class="col-11" style="align-self: center">
                <strong>Username</strong>
            </div>
        </div>
<!--    post:-->
        <div class="row">
            <div class="col-12 text-center">
                <img src="./images/blank_avatar.png" alt="" style="width: 100%">
            </div>
        </div>
<!--    action buttons:-->
        <div class="row d-flex"  style="padding-top: 1rem">
            <div class="col-1" style=" max-width: 7%">
                <button style="border: none; outline: none; background: none;"><i class="fa fa-heart-o" aria-hidden="true" style="font-size: 1.5rem; padding-top: .2rem; font-weight: bold"></i></button>
            </div>
            <div class="col-1" style="padding-left: 0">
                <button style="border: none; outline: none; background: none;"><i class="fa fa-comment-o" aria-hidden="true" style="font-size: 1.7rem; position: relative; left: 0; font-weight: bold"></i></button>
            </div>
<!--    likes:-->
        </div>
        <div class="row" style="padding-top: .5rem">
            <div class="col-12">
                <span>151.515 likes</span>
            </div>
        </div>
<!--    comments:-->
        <div class="row" style="padding-top: .5rem">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <span><strong>Username</strong></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span>Comment</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

</body>
</html>
