<?php
include_once(__DIR__."/includes/nav.php");
include_once(__DIR__."/classes/Post.php");
include_once(__DIR__."/classes/User.php");
include_once(__DIR__."/includes/checkSession.php");

if (isset($_GET['search'])) {
    try {
        $posts = Post::search($_GET['search']);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}else{
    try {
        $user = User::fetchUserByUsername($_GET["user"]);
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
        <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cssgram/0.1.10/cssgram.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
    <title>Pixelab</title>
</head>
<body>
    <div class="container">
        <div class="row pb-5 pt-5 profile-info">
            <div class="col-3 text-center">
                <?php if (!empty($user->getAvatar())) : ?>
                    <img src="./uploads/avatars/<?php echo $user->getAvatar();?>" class="rounded-circle w-10-vw h-10-vw object-fit-cover avatar-img" alt="uploaded avatar"/>
                <?php else: ?>
                    <img src="./images/blank_avatar.png" class="rounded-circle max-w-10-vw  avatar-img" alt="blank avatar"/>
                <?php endif; ?>
            </div>
            <div class="col-3 align-self-center">
                <h1 class="d-inline"><?php echo htmlspecialchars($user->getUsername());?></h1>
                <p id="followerCount"><?php echo sizeof($user->fetchFollowers()); ?> Followers</p>
            </div>
            <div class="col-3 align-self-center">
                <p><?php echo htmlspecialchars($user->getBio());?></p>
            </div>
            <?php if ($_GET['user'] != $_SESSION['user']): ?>
            <div class="col-3 align-self-center text-center">
                <button class="btn btn-primary" id="followButton"
                        data-isfollowing="<?php echo(in_array($_SESSION['user'],User::fetchUserByUsername($_GET['user'])->getFollowers()))?"1":"0"; ?>"
                        data-follower="<?php echo $_SESSION['user']?>"
                        data-following="<?php echo $_GET['user']?>">
                    <?php echo htmlspecialchars((in_array($_SESSION['user'],User::fetchUserByUsername($_GET['user'])->getFollowers())? "Unfollow":"Follow")); ?>
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="container d-flex flex-wrap m-auto posts-container">
    <?php try {
        foreach (Post::fetchPostsByUserId($user->getId()) as $post) : ?>
            <?php if (!empty($post->getImage())) : ?>
                <a href="./postDetail.php?<?php try {
                    echo "u=" . htmlspecialchars($_GET['user']) . "&" . "pid=" . htmlspecialchars($post->getId());
                } catch (Exception $e) {
                } ?>" class="postPreview text-white">
                    <figure class="profilePageFigure <?php echo $post->getFilter();?>">
                    <img class="profilePagePost" src="./uploads/posts/<?php echo $post->getImage(); ?>"
                         alt="post image">
                    </figure>
                    <div class="centered">
                    <span class="overlay d-none">
                        <i class="fa fa-heart btn-icon font-size-1-rem" aria-hidden="true"></i>
                        <?php echo " " . sizeof($post->getLikes()); ?>
                        <i class="fa fa-comment btn-icon font-size-1-rem" aria-hidden="true"></i>
                        <?php echo "  " . sizeof($post->getComments()); ?>
                    </span>
                    </div>
                </a>
            <?php else: ?>
                <a href="./postDetail.php?<?php try {
                    echo "u=" . htmlspecialchars($_GET['user']) . "&" . "pid=" . htmlspecialchars($post->getId());
                } catch (Exception $e) {
                } ?>" class="postPreview text-white">
                    <img class="profilePagePost w-256-px h-256-px" src="./images/blank_post.jpg" alt="blank post">
                    <div class="centered">
                    <span class="overlay d-none">
                        <i class="fa fa-heart btn-icon font-size-1-rem" aria-hidden="true"></i>
                        <?php echo sizeof($post->getLikes()); ?>
                        <i class="fa fa-comment btn-icon font-size-1-rem" aria-hidden="true"></i>
                        <?php echo sizeof($post->getComments()); ?>
                    </span>
                    </div>
                </a>
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
