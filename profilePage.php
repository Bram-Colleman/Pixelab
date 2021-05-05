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
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
</head>
<body>
<script src="https://use.fontawesome.com/2dd2522a24.js"></script>
    <div class="container">
        <?php if (!empty($user->getAvatar())) : ?>
            <img src="./uploads/avatars/<?php echo $user->getAvatar();?>" class="rounded-circle max-w-10-vw" alt="uploaded avatar"/>
        <?php else: ?>
            <img src="./images/blank_avatar.png" class="rounded-circle max-w-10-vw" alt="blank avatar"/>
        <?php endif; ?>
        <h1 class="d-inline"><?php echo $user->getUsername();?></h1>
    </div>
    <div class="container d-flex flex-wrap m-auto">
    <?php foreach (Post::fetchPostsByUserId($user->getId()) as $post) : ?>
        <?php if (!empty($post->getImage())) : ?>
        <div class="postPreview">
            <img class="profilePagePost" src="./uploads/posts/<?php echo $post->getImage();?>" alt="post image" >
            <div class="centered">
                <span class="overlay d-none">
                    <i class="fa fa-heart btn-icon font-size-1-rem" aria-hidden="true"></i>
                    <?php echo " ". sizeof($post->getLikes());?>
                    <i class="fa fa-comment btn-icon font-size-1-rem" aria-hidden="true"></i>
                    <?php echo "  ". sizeof($post->getComments());?>
                </span>
            </div>
        </div>
        <?php else: ?>
        <div class="postPreview">
            <img class="profilePagePost w-256-px h-256-px" src="./images/blank_post.jpg" alt="blank post">
            <div class="centered">
                <span class="overlay d-none">
                    <i class="fa fa-heart btn-icon font-size-1-rem" aria-hidden="true"></i>
                    <?php echo sizeof($post->getLikes());?>
                    <i class="fa fa-comment btn-icon font-size-1-rem" aria-hidden="true"></i>
                    <?php echo sizeof($post->getComments());?>
                </span>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
    </div>


<script>
    $(".postPreview").hover(
        function () {
            $(".overlay").removeClass("d-none");
            $(".profilePagePost:hover").addClass("profilePagePostHover");
        },
        function () {
            $(".overlay").addClass("d-none");
            $(".profilePagePost").removeClass("profilePagePostHover");
        }
    )
</script>
</body>
</html>
