<?php
    include_once("nav.php");
    include_once(__DIR__ . "/classes/User.php");
    include_once(__DIR__ . "/classes/Post.php");

    if(!isset($_SESSION)) {
        session_start();
    }

    if(!empty($_POST)) {
        try {
            Post::uploadPost(User::fetchUserByEmail($_SESSION['email'])->getId(), $_POST['description']);
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
<body class="login-body">
<div class="background-overlay"></div>
<div class="flexbox">
    <div class="login-card">
        <div style="width: 75%;  margin-left: 12.5%; margin-top: 10%" class="row">
            <form method="post" enctype="multipart/form-data">
            <div class="col">
                <label for="file-input">
                    <img src="./images/blank_post.jpg" class="rounded-circle" style="width: 20vw;" role='button' alt=""/>
                </label>
                <input type="file" id="file-input" name="postImage" style="display: none;" required>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="inputDescription" class="form-label">Description</label>
                            <textarea class="form-control" style="height: 15rem;" id="inputDescription" name="description" required></textarea>
                        </div>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            </div>

        </div>

    </div>

</div>

</body>
</html>
