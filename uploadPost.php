<?php
include_once("nav.php");
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Post.php");

if (!isset($_SESSION)) {
    session_start();
}

if (!empty($_POST)) {
    try {
        Post::uploadPost(User::fetchUserByUSername($_SESSION["user"]), $_POST['description']);
        header("Location: feed.php");
    } catch (Exception $e) {
        var_dump($_SESSION);
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
    <title>Upload Post</title>
</head>
<body>
<div class="container">
    <form class="form-inline" method="post" enctype="multipart/form-data">
        <div style="width: 75%;  margin-left: 12.5%; margin-top: 10%" class="row">
            <div class="col-md-6">
                <label for="file-input">
                    <img src="./images/blank_post.jpg" class="rounded-circle" style="width: 20vw;" role='button'
                         alt=""/>
                </label>
                <input type="file" id="file-input" name="postImage" style="display: none;" required>
            </div>
            <div class="col-md-6">
                <label for="inputDescription" class="form-label">Description</label>
                <textarea class="form-control" style="height: 15rem; resize: none" id="inputDescription"
                          name="description" required></textarea>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </div>
        </div>
    </form>
</div>

</body>
</html>
