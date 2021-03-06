<?php
include_once(__DIR__ . "/includes/nav.php");
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/includes/checkSession.php");

if (isset($_GET['search'])) {
    try {
        $posts = Post::search($_GET['search']);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

try {

    if(!isset($_SESSION)) {
        session_start();
    }

    $user = User::fetchUserByUSername($_SESSION["user"]);

} catch (Exception $e) {
    $error = $e->getMessage();
}

if (!empty($_POST)) {
    if (!empty($user)) {
//        $user->uploadAvatar($_POST['oldPassword']);
        if(isset($_POST['updateProfile'])) {
            try {
                $user->updateUser($_POST['username'], $_POST['bio'], $_POST['email'], $_POST['oldPassword'], $_POST['newPassword']);
            } catch (Exception $e) {
            $error = $e->getMessage();
        }

        }

        if(isset($_POST['deleteAvatar'])) {
            $user->deleteAvatar();
        }
    }
}


?>
<?php if (!empty($user)) : ?>
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
        <title>Profile</title>
    </head>
    <body>
    <?php if (!empty($_POST)): ?>
        <?php if (password_verify($_POST['oldPassword'], $user->getPassword()) === false): ?>
            <div class="container-fluid w-25 pt-1 text-center">
                <div class="alert alert-danger" id="invalidPassword" role="alert">
                    Incorrect password.
                </div>
            </div>
<!--        <?php /* else: */?>
            <div class="container-fluid w-25 pt-1 text-center">
                <div class="alert alert-danger" id="invalidPassword" role="alert">
                    The username or email you entered already exists.
                </div>
            </div>-->
        <?php endif; ?>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger text-center">
            <p><?php echo $error ?></p>
        </div>
    <?php endif; ?>
    <div class="flexbox">
        <div class="justify-content-center w-50 m-5-auto-auto">
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative w-100 h-100 d-flex justify-content-center">
                        <label for="file-input">
                            <?php if (!empty($user)) : ?>
                                <?php if (!empty($user->getAvatar())) : ?>
                                    <img id="uploadedImage" src="./uploads/avatars/<?php echo $user->getAvatar();?>" class="rounded-circle w-20-vw h-20-vw object-fit-cover" role='button' alt="avatar"/>
                                <?php else: ?>
                                    <img id="uploadedImage" src="./images/blank_avatar.png" class="rounded-circle w-20-vw" role='button' alt="blank avatar"/>
                                <?php endif; ?>
                            <?php endif; ?>
                        </label>
                            <input class="d-none" id="file-input" type="file" name="avatar"/>
                            <input type="submit" class="position-absolute bottom-0 btn btn-danger" value="Delete picture" name="deleteAvatar">
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="inputUsername" class="form-label fw-bold">Username</label>
                                <input type="text" class="form-control" id="inputUsername"
                                       value="<?php echo htmlspecialchars($user->getUsername()); ?>" name="username">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="inputBio" class="form-label fw-bold">Bio</label>
                                <input type="text" class="form-control" id="inputBio"
                                       value="<?php echo htmlspecialchars($user->getBio()); ?>" name="bio">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="inputEmail" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" id="inputEmail"
                                       value="<?php echo htmlspecialchars($user->getEmail()); ?>" name="email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="inputOldPassword" class="form-label fw-bold">Current Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="inputOldPassword" name="oldPassword" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="inputNewPassword" class="form-label fw-bold">New Password</label>
                                    <input type="password" class="form-control" id="inputNewPassword" name="newPassword">
                                </div>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Update profile" name="updateProfile">
                    </div>
                </div>
            </form>
        </div>


    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
    <script src="js/liveImagePreview.js"></script>
    </body>
    </html>
<?php endif; ?>