<?php
include_once(__DIR__ . "/includes/nav.php");
include_once(__DIR__ . "/classes/Post.php");
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Comment.php");
include_once(__DIR__ . "/includes/checkSession.php");

if(empty($_POST)){
    try {

            $posts = Post::fetchRecentPosts();
    } catch (Exception $e) {
        $error = $e->getMessage();

    }
}else{
    try {
        $posts = Post::search('description', $_POST['search']);
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
    <title>Pixelab</title>
</head>
<body>
<?php if (!empty($posts)) {
    foreach ($posts as $post): ?>
    <?php
        $username = $post->getUser();
        try {
            $user = User::fetchUserByUsername($post->getUser());
        } catch (Exception $e) {
        }
    ?>
        <div class="container-fluid shadow w-35 pt-1 pb-1 mt-5">
            <!--    username and avatar:-->
            <div class="row h-5 mb-2">

                <div class="col-1 align-self-center max-w-6">
                    <?php if (!empty($user)) {
                        if (!empty($user->getAvatar())) : ?>
                            <img src="./uploads/avatars/<?php echo $user->getAvatar();?>" class="rounded-circle avatarIcon" role='button' alt="avatar image"/>
                        <?php else: ?>
                            <img src="./images/blank_avatar.png" class="rounded-circle max-w-1-half-vw" role='button' alt="blank avatar"/>
                        <?php endif;
                    } ?>
                </div>
                <div class="col-9 align-self-center">
                    <a class="text-decoration-none text-black fw-bold" href="./profilePage.php?user=<?php try {
                        echo $post->getUser();
                    } catch (Exception $e) {
                    } ?>"><?php echo $post->getUser(); ?></a>
                </div>
                <div class="col-2 align-self-center justify-content-end timestamp-post">
                    <?php
                    $a = new DateTime($post->getTimestamp());
                    $b = new DateTime(date('Y-m-d H:i:s'));


                    $intervalminutes = $a->diff($b)->i;
                    $intervalhourstomin =  ($a->diff($b)->H) *60;
                    $intervaldaystomin =  ($a->diff($b)->d) *24*60;
                    $interval = $intervalminutes+$intervalhourstomin+$intervaldaystomin-60;


                    $intervalhours =  ($a->diff($b)->h);
                    $intervaldays =  ($a->diff($b)->d);

                    if ($interval<0) {
                        echo "Today";
                    }
                    elseif ($intervaldays> 0 && $intervaldays<7) {
                        echo $intervaldays . "d ago";
                    }
                    elseif ($intervaldays>7) {
                        echo floor($intervaldays/7) . "w ago"; // => 1

                    }
                    ?>
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
                        <a href="#" class="border-0 outline-none bg-none text-black btn-like"
                               data-postid="<?php echo $post->getId();?>"
                               data-liked="<?php echo in_array($_SESSION['user'],$post->getLikes())? "1": "0";?>"
                               data-userid="<?php echo User::fetchUserByUsername($_SESSION['user'])->getId();?>">
                            <i class="fa <?php echo in_array($_SESSION['user'],$post->getLikes())? "fa-heart": "fa-heart-o";?> btn-icon" aria-hidden="true" ></i>
                        </a>
                        <a href="#" class="border-0 outline-none bg-none text-blac btn-report">Report</a>
<!--                    --><?php //var_dump(Post::fetchPostById(44)); ?>
                </div>
                <!--    likes:-->
            </div>
            <div class="row pt-half">
                <div class="col-12">
                    <span>
                        <span class="like-count" id="<?php echo $post->getId();?>">
                            <?php echo sizeof($post->getLikes()); ?>
                        </span>
                        likes
                    </span>
                </div>
            </div>
            <!--    Description:-->
            <div class="row pt-half description">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 pb-2">
                            <span><strong><?php echo $post->getUser(); ?> </strong></span><span><?php echo $post->getDescription();?></span>
                        </div>
                    </div>
                </div>
            </div>
            <!--    comments:-->
            <?php if (!empty($post->getComments())): ?>
                <?php foreach ($post->getComments() as $comment) : ?>
                <div class="row pt-half comment">
                    <div class="col-12 pb-2">
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
            <!-- comment input field :-->
            <div class="d-flex border-top-gray">
                    <input class="w-100 border-0 addComment py-half-rem" type="text" name="comment" placeholder="Add a comment as <?php echo $_SESSION['user']; ?>..."
                           data-postid="<?php echo $post->getId(); ?>"
                           data-username="<?php echo $_SESSION['user']; ?>">
            </div>
        </div>
    <?php endforeach;
} ?>

<script src="https://use.fontawesome.com/2dd2522a24.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="js/liveCommentPost.js"></script>
<script src="js/liveLikePost.js"></script>
<script src="js/liveReportPost.js"></script>
</body>
</html>
