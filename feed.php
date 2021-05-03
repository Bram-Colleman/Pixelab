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
        <div class="container-fluid shadow-sm"
             style="width: 35%; padding-top: 1rem; padding-bottom: 1rem; margin-top: 1.5rem;">
            <!--    username and avatar:-->
            <div class="row" style="height: 5%;">

                <div class="col-1" style="align-self: center">
                    <?php if (!empty($user)) {
                        if (!empty($user->getAvatar())) : ?>
                            <img src="./uploads/avatars/<?php echo $user->getAvatar();?>" class="rounded-circle" style="max-width: 1.5vw;" role='button' alt=""/>
                        <?php else: ?>
                            <img src="./images/blank_avatar.png" class="rounded-circle" style="max-width: 1.5vw;" role='button' alt=""/>
                        <?php endif;
                    } ?>
                </div>
                <div class="col-11" style="align-self: center">
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
                            <img src="./uploads/posts/<?php echo $post->getImage();?>" alt="" style="max-width: 100%; min-width: 100%">
                        <?php else: ?>
                            <img src="./images/blank_post.jpg" alt="" style="max-width: 100%; min-width: 100%">
                        <?php endif;
                    } ?>
                </div>
            </div>
            <!--    action buttons:-->
            <div class="row d-flex" style="padding-top: 1rem">
                <div class="col-1" style=" max-width: 7%">
                    <button style="border: none; outline: none; background: none;"><i class="fa fa-heart-o"
                                                                                      aria-hidden="true"
                                                                                      style="font-size: 1.5rem; padding-top: .2rem; font-weight: bold"></i>
                    </button>
                </div>
                <div class="col-1" style="padding-left: 0">
                    <button style="border: none; outline: none; background: none;"><i class="fa fa-comment-o"
                                                                                      aria-hidden="true"
                                                                                      style="font-size: 1.7rem; position: relative; left: 0; font-weight: bold"></i>
                    </button>
                </div>
                <!--    likes:-->
            </div>
            <div class="row" style="padding-top: .5rem">
                <div class="col-12">
                    <span><?php echo sizeof($post->getLikes()); ?> likes</span>
                </div>
            </div>
            <!--    Description:-->
            <div class="row" style="padding-top: .5rem">
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
                <div class="row" style="padding-top: .5rem">
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
