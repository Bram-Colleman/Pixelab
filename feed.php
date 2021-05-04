<?php
include_once("nav.php");
include_once(__DIR__."/classes/Post.php");
include_once(__DIR__."/classes/User.php");

try {
    $posts = Post::fetchRecentPosts();
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
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="styles/style.css">
    <title>Pixelab</title>
</head>
<body>
<script src="https://use.fontawesome.com/2dd2522a24.js"></script>

<?php if (!empty($posts)) {
    foreach ($posts as $post): ?>
    <?php
        $username = $post->getUser();
        try {
            $user = User::fetchUserByUsername($post->getUser());
        } catch (Exception $e) {
        }
    ?>
        <div class="container-fluid shadow-sm w-35 pt-1 pb-1 mt-5">
            <!--    username and avatar:-->
            <div class="row h-5 mb-2">

                <div class="col-1 align-self-center">
                    <?php if (!empty($user)) {
                        if (!empty($user->getAvatar())) : ?>
                            <img src="./uploads/avatars/<?php echo $user->getAvatar();?>" class="rounded-circle max-w-1-half-vw" role='button' alt="avatar image"/>
                        <?php else: ?>
                            <img src="./images/blank_avatar.png" class="rounded-circle max-w-1-half-vw" role='button' alt="blank avatar"/>
                        <?php endif;
                    } ?>
                </div>
                <div class="col-11 align-self-center">
                    <a href="./profilePage.php?user=<?php try {
                        echo $post->getUser();
                    } catch (Exception $e) {
                    } ?>"><?php echo $post->getUser(); ?></a>
                </div>
            </div>
            <!--    post:-->
            <div class="row">
                <div class="col-12 text-center p-0">
                    <?php if (!empty($post)) {
                        if (!empty($post->getImage())) : ?>
                            <img class="max-w-100 min-w-100" src="./uploads/posts/<?php echo $post->getImage();?>" alt="post image">
                        <?php else: ?>
                            <img class="max-w-100 min-w-100" src="./images/blank_post.jpg" alt="blank post image">
                        <?php endif;
                    } ?>
                </div>
            </div>
            <!--    action buttons:-->
            <div class="row d-flex pt-1">
                <div class="col-1 max-w-7">
                    <button class="border-0 outline-none bg-none">
                        <i class="fa fa-heart-o btn-icon" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="col-1 px-0">
                    <button class="border-0 outline-none bg-none">
                        <i class="fa fa-comment-o btn-icon font-size-1-6" aria-hidden="true"></i>
                    </button>
                </div>
                <!--    likes:-->
            </div>
            <div class="row pt-half">
                <div class="col-12">
                    <span><?php echo sizeof($post->getLikes()); ?> likes</span>
                </div>
            </div>
            <!--    Description:-->
            <div class="row pt-half">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <span><strong><?php echo $post->getUser(); ?> </strong></span><span><?php echo $post->getDescription();?></span>
                        </div>
                    </div>
                </div>
            </div>
            <!--    comments:-->
            <?php if (!empty($post->getComments())): ?>
                <?php foreach ($post->getComments() as $comment) : ?>
                <div class="row pt-half">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <span><strong><?php echo $comment['username'];?></strong></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <span><?php echo $comment['content'];?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endforeach;
} ?>

</body>
</html>
