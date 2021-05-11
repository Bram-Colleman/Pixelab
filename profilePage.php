<?php
include_once(__DIR__."/includes/nav.php");
include_once(__DIR__."/classes/Post.php");
include_once(__DIR__."/classes/User.php");
include_once(__DIR__."/includes/checkSession.php");

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
    <div class="container">
        <div class="row pb-5 pt-5">
            <div class="col-3 text-center">
                <?php if (!empty($user->getAvatar())) : ?>
                    <img src="./uploads/avatars/<?php echo $user->getAvatar();?>" class="w-10-rem h-10-rem object-fit-cover rounded-circle" alt="uploaded avatar"/>
                <?php else: ?>
                    <img src="./images/blank_avatar.png" class="rounded-circle max-w-10-vw" alt="blank avatar"/>
                <?php endif; ?>
            </div>
            <div class="col-6 align-self-center">
                <h1 class="d-inline"><?php echo $user->getUsername();?></h1>
                <p id="followerCount"><?php echo sizeof($user->fetchFollowers()); ?> Followers</p>
            </div>
            <?php if ($_GET['user'] != $_SESSION['user']): ?>
            <div class="col-3 align-self-center text-center">
                <button class="btn btn-primary" id="followButton"
                        data-isfollowing="<?php echo(in_array($_SESSION['user'],User::fetchUserByUsername($_GET['user'])->getFollowers()))?"1":"0"; ?>"
                        data-follower="<?php echo $_SESSION['user']?>"
                        data-following="<?php echo $_GET['user']?>">
                    <?php echo(in_array($_SESSION['user'],User::fetchUserByUsername($_GET['user'])->getFollowers())? "Unfollow":"Follow"); ?>
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="container d-flex flex-wrap m-auto">
    <?php try {
        foreach (Post::fetchPostsByUserId($user->getId()) as $post) : ?>
            <?php if (!empty($post->getImage())) : ?>
                <div class="postPreview">
                    <img class="profilePagePost" src="./uploads/posts/<?php echo $post->getImage(); ?>"
                         alt="post image">
                    <div class="centered">
                    <span class="overlay d-none">
                        <i class="fa fa-heart btn-icon font-size-1-rem" aria-hidden="true"></i>
                        <?php echo " " . sizeof($post->getLikes()); ?>
                        <i class="fa fa-comment btn-icon font-size-1-rem" aria-hidden="true"></i>
                        <?php echo "  " . sizeof($post->getComments()); ?>
                    </span>
                    </div>
                </div>
            <?php else: ?>
                <div class="postPreview">
                    <img class="profilePagePost w-256-px h-256-px" src="./images/blank_post.jpg" alt="blank post">
                    <div class="centered">
                    <span class="overlay d-none">
                        <i class="fa fa-heart btn-icon font-size-1-rem" aria-hidden="true"></i>
                        <?php echo sizeof($post->getLikes()); ?>
                        <i class="fa fa-comment btn-icon font-size-1-rem" aria-hidden="true"></i>
                        <?php echo sizeof($post->getComments()); ?>
                    </span>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach;
    } catch (Exception $e) {
    } ?>
    </div>

<script src="https://use.fontawesome.com/2dd2522a24.js"></script>
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
    $(".postPreview").hover(
        function () {
            $(".postPreview:hover .overlay").removeClass("d-none");
            $(".profilePagePost:hover").addClass("profilePagePostHover");
        },
        function () {
            $(" .overlay").addClass("d-none");
            $(".profilePagePost").removeClass("profilePagePostHover");
        }
    )
</script>
    <script src="./js/liveFollowUser.js"></script>
</body>
</html>
