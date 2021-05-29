<?php
include_once(__DIR__ . "/includes/nav.php");
include_once(__DIR__ . "/classes/Post.php");
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Comment.php");
include_once(__DIR__ . "/includes/checkSession.php");

try {
    $user = User::fetchUserByUsername($_GET["u"]);
    $post = Post::fetchPostById($_GET['pid']);
    $newComment = new Comment();
    $newComment->setPostId($_GET['pid']);
} catch (Exception $e) {
    $error = $e->getMessage();
}


if(!empty($_POST['btn-delete'])) {
    Post::deletePost($_GET['pid']);
    header('Location: ' . 'profilePage.php?user=' . $_GET['u']);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cssgram/0.1.10/cssgram.min.css">
    <title>Pixelab</title>
</head>
<body>

<div>
    <div class="container-fluid shadow w-65 mt-5 mb-5 post-card">
        <!-- Username and avatar: -->
        <div class="row h-5 mb-1">
            <div class="col-auto flex-fill align-self-center text-center p-0">
                <a href="./profilePage.php?user=<?php try {
                    echo htmlspecialchars($_GET['u']);
                } catch (Exception $e) {
                } ?>">
                    <?php if (!empty($user->getAvatar())) : ?>
                        <img src="./uploads/avatars/<?php echo $user->getAvatar() ?>" class="rounded-circle avatarIcon"
                             alt="uploaded avatar">
                    <?php else: ?>
                        <img id="uploadedImage" src="./images/blank_avatar.png" class="rounded-circle avatarIcon" role='button' alt="blank avatar"/>
                    <?php endif; ?>
                </a>
            </div>
            <div class="col-4 flex-fill align-self-center p-0">
                <a class="text-decoration-none text-black fw-bold" href="./profilePage.php?user=<?php try {
                    echo htmlspecialchars($_GET['u']);
                } catch (Exception $e) {
                } ?>"><?php echo $_GET['u'] ?></a>
                <a href="#" class="text-decoration-none text-black"><?php echo htmlspecialchars($post->getLocation()); ?></a>
            </div>
            <div class="col-6 d-flex flex-fill align-self-center justify-content-end timestamp-post">
                <p class="mb-0"><?php echo "Posted " . $post->postedTimeAgo($_GET['pid']) . " ago"; ?></p>
            </div>
        </div>
        <!-- Post: -->
        <div class="row post-content">
            <!-- Post image: -->
            <div class="col-6 flex-fill px-0">
                <figure class="<?php echo $post->getFilter();?>">
                <img id="postImage" src="./uploads/posts/<?php echo $post->getImage(); ?>"
                     class="w-100" alt="blank post">
                </figure>
            </div>
            <!-- Comments: -->
            <div class="col-5 flex-fill position-relative comment-section">
                <div class="pb-2 border-bottom-gray">
                    <span class="fw-bold"><?php echo htmlspecialchars($_GET['u']); ?></span><span> <?php echo htmlspecialchars($post->getDescription());?></span>
                </div>
                <div id="setHeight" class="overflow-y-scroll overflow-x-disabled commentList">
                    <?php if (!empty($post::fetchComments($_GET['pid']))): ?>
                        <?php foreach ($post::fetchComments($_GET['pid']) as $comment) : ?>
                            <div class="row pt-half comment">
                                <div class="col-12 pb-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="fw-bold"><?php echo htmlspecialchars($comment['username']); ?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <span><?php echo htmlspecialchars($comment['content']); ?></span>
                                            <span class="timestamp-comment"><?php echo Comment::timeAgo($comment['id']) . " ago"; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <!-- Likes: -->
                <div class="row">
                    <div class="col-12 position-absolute bottom-40 border-top-gray bg-white h-35">
                        <a href="#" class="border-0 outline-none bg-none text-decoration-none text-black btn-like"
                            data-postid="<?php echo $_GET['pid'];?>"
                            data-liked="<?php echo in_array($_SESSION['user'], $post::fetchLikes($_GET['pid']))? "1": "0";?>"
                            data-userid="<?php echo User::fetchUserByUsername($_SESSION['user'])->getId();?>">
                            <i class="fa <?php echo in_array($_SESSION['user'], $post::fetchLikes($_GET['pid']))? "fa-heart": "fa-heart-o";?> btn-icon" aria-hidden="true"></i>
                        </a>
                        <span class="like-count" id="<?php echo $_GET['pid']; ?>">
                            <?php echo count($post::fetchLikes($_GET['pid'])); ?>
                        </span>
                        <span>
                            likes
                        </span>
                        <a href="#" class="position-absolute right-12 top-5-half border-0 outline-none bg-none text-decoration-none text-black btn-report text-end"
                            data-postid="<?php echo $_GET['pid']; ?>"
                        >Report</a>
                    </div>
                </div>
                <!-- Comment input field: -->
                <div class="row d-flex position-absolute bottom-0 w-100">
                    <input class="border-0 addComment py-half-rem" type="text" name="comment"
                           placeholder="Add a comment as <?php echo htmlspecialchars($_SESSION['user']); ?>..."
                           data-postid="<?php echo $_GET['pid']; ?>"
                           data-username="<?php echo htmlspecialchars($_SESSION['user']); ?>">
                </div>
            </div>
        </div>
    </div>
    <?php if($_SESSION['user'] == $_GET['u']) : ?>
        <form method="post" class="d-flex justify-content-center mb-5">
            <input type="submit" class="btn btn-danger m-4" name="btn-delete" value="Delete post">
        </form>
    <?php endif; ?>
</div>

<script src="https://use.fontawesome.com/2dd2522a24.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="scripts/getPostImage.js"></script>
<script src="js/liveLikePost.js"></script>
<script src="js/liveReportPost.js"></script>
<script src="js/liveCommentPost.js"></script>
</body>
</html>