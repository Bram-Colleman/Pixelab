<?php
include_once(__DIR__ . "/includes/nav.php");
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Post.php");
include_once(__DIR__ . "/includes/checkSession.php");

if (!isset($_SESSION)) {
    session_start();
}

if (!empty($_POST)) {
    try {
        Post::uploadPost($_POST['description']);
        header("Location: index.php");
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
    <title>Upload Post</title>
</head>
<body>
<?php if (isset($error)): ?>
    <div class="alert alert-danger text-center">
        <p><?php echo $error ?></p>
    </div>
<?php endif; ?>
<div class="container">
    <form class="form-inline" method="post" enctype="multipart/form-data">
        <div class="row w-75 ml-12-half mt-10">
            <div class="col-md-6">
                <label for="file-input">
                    <img id="uploadedImage" src="./images/blank_post.jpg" class="rounded-circle w-20-vw h-20-vw object-fit-cover" role='button'
                         alt=""/>
                </label>
                <input class="d-none" type="file" id="file-input" name="postImage" required>
            </div>
            <div class="col-md-6">
                <label for="inputDescription" class="form-label">Description</label>
                <textarea class="form-control h-15-rem resize-none" id="inputDescription"
                          name="description" required></textarea>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="js/liveImagePreview.js"></script>

</body>
</html>
