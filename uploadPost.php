<?php
include_once(__DIR__ . "/includes/nav.php");
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Post.php");
include_once(__DIR__ . "/includes/checkSession.php");

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_GET['search'])) {
    try {
        $posts = Post::search(urlencode($_GET['search']));
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

if (!empty($_POST)) {
    try {
        Post::uploadPost($_POST['description'], $_POST['filter'], $_POST['long'], $_POST['lat']);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cssgram/0.1.10/cssgram.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>
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
        <div class="row w-75 ml-12-half mt-10  ">
            <div class="col-md-6 d-flex justify-content-center">
                <label for="file-input" >
                    <figure id="previewImage" class="h-20-vw w-20-vw justify-content-center m-auto mb-1" >
                        <img id="uploadedImage" src="./images/blank_post.jpg" class="w-20-vw h-20-vw object-fit-cover justify-content-center" role='button'
                             alt=""/>
                    </figure>
                    <div >
                        <input type="radio" id="filter1"
                               name="filter" value="" class="d-none">
                        <label for="filter1" class="btn-radio" data-filter="">No filter</label>
                        <input type="radio" id="filter2"
                               name="filter" value="_1977" class="d-none">
                        <label for="filter2" class="btn-radio" data-filter="_1977">1977</label>
                        <input type="radio" id="filter3"
                               name="filter" value="aden" class="d-none">
                        <label for="filter3" class="btn-radio" data-filter="aden">aden</label>
                        <input type="radio" id="filter4"
                               name="filter" value="brannan" class="d-none">
                        <label for="filter4" class="btn-radio" data-filter="brannan">Brannan</label>
                        <input type="radio" id="filter5"
                               name="filter" value="brooklyn" class="d-none">
                        <label for="filter5" class="btn-radio" data-filter="brooklyn">Brooklyn</label>
                        <input type="radio" id="filter6"
                               name="filter" value="clarendon" class="d-none">
                        <label for="filter6" class="btn-radio" data-filter="clarendon">Clarendon</label>
                        <input type="radio" id="filter7"
                               name="filter" value="earlybird" class="d-none">
                        <label for="filter7" class="btn-radio" data-filter="earlybird">Earlybird</label>
                        <input type="radio" id="filter8"
                               name="filter" value="gingham" class="d-none">
                        <label for="filter8" class="btn-radio" data-filter="gingham">Gingham</label>
                        <input type="radio" id="filter9"
                               name="filter" value="hudson" class="d-none">
                        <label for="filter9" class="btn-radio" data-filter="hudson">Hudson</label>
                    </div>
                    <div class="d-none">
                        <label for="long"></label>
                        <input type="text" name="long" id="long" value="">

                        <label for="lat"></label>
                        <input type="text" name="lat" id="lat" value="">
                    </div>
                </label>
                <input class="d-none" type="file" id="file-input" name="postImage" required>
            </div>
            <div class="col-md-6">
                <label for="inputDescription" class="form-label">Description</label>
                <textarea class="form-control h-15-rem resize-none" id="inputDescription"
                          name="description" required></textarea>
                <button type="submit" class="btn btn-primary mt-2" id="btn-post">Submit</button>
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="js/liveImagePreview.js"></script>
<script src="scripts/getPostLocation.js"></script>
</body>
</html>
