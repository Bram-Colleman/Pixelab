<?php
include_once(__DIR__ . "/includes/nav.php");
include_once(__DIR__ . "/classes/Post.php");
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/includes/checkSession.php");
 if($_SESSION["userRole"] === "user"){
    header("Location: index.php");
 }

if (isset($_GET['search'])) {
    try {
        $posts = Post::search($_GET['search']);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
if(empty($_POST)){
    try {
        $posts = Post::loadReportedPosts();
        //var_dump(Post::loadReportedPosts());
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
    <link rel="stylesheet" href="styles/style.css">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cssgram/0.1.10/cssgram.min.css">
    <title>Pixelab</title>
</head>
<body>
<div id="PostContainer">
<?php if (!empty($posts)) {
    foreach ($posts as $post): ?>
    <?php
        $username = $post->getUser();
        try {
            $user = User::fetchUserByUsername($post->getUser());
        } catch (Exception $e) {
        }
    ?>
        <div class="container-fluid shadow w-35 pt-1 pb-1 mt-5" data-postid="<?php echo $post->getId(); ?>">
            <div class="row pt-half">
                <div class="col-12">
                    <a href="#" class="btn btn-success btn-keepPost">Keep post</a>
                    <a href="#" class="btn btn-danger btn-deletePost">Delete post</a>
                </div>
            </div>
            <!--    username and avatar:-->
            <div class="row h-5 mb-1">
                <div class="col-1 align-self-center max-w-6">
                    <?php if (!empty($user)) {
                        if (!empty($user->getAvatar())) : ?>
                            <img src="./uploads/avatars/<?php echo $user->getAvatar();?>" class="rounded-circle avatarIcon" role='button' alt="avatar image"/>
                        <?php else: ?>
                            <img src="./images/blank_avatar.png" class="rounded-circle max-w-1-half-vw" role='button' alt="blank avatar"/>
                        <?php endif;
                    } ?>
                </div>
                <div class="col-8 align-self-center">
                    <a class="text-decoration-none text-black fw-bold" href="./profilePage.php?user=<?php try {
                        echo htmlspecialchars($post->getUser());
                    } catch (Exception $e) {
                    } ?>"><?php echo htmlspecialchars($post->getUser()); ?></a>
                </div>
                <div class="col-3 align-self-center justify-content-end timestamp-post"><?php echo "Posted " . $post->postedTimeAgo($post->getId()) . " ago"; ?></div>
            </div>
            <!--    post:-->
            <div class="row">
                <div class="col-12 text-center p-0">
                    <?php if (!empty($post)) {  
                        if (!empty($post->getImage())) : ?>
                            <figure class="<?php echo $post->getFilter();?>">
                                    <img class="max-w-100 min-w-100" src="./uploads/posts/<?php echo $post->getImage();?>" alt="post image">
                            </figure>
                        <?php else: ?>
                            <img class="max-w-100 min-w-100" src="./images/blank_post.jpg" alt="blank post image">
                        <?php endif;
                    } ?>
                </div>
            </div>
            <!--    description:-->
            <div class="row pt-half description">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 pb-2">
                            <span class="fw-bold"><?php echo htmlspecialchars($post->getUser()); ?></span><span> <?php echo htmlspecialchars($post->getDescription());?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;
} ?>

</div>

<script src="https://use.fontawesome.com/2dd2522a24.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="js/liveJudgePosts.js"></script>
</body>
</html>
