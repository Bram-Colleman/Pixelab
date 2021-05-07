<?php
    session_start();
    include_once("./classes/User.php");
    try {
        $currentUser = User::fetchUserByUsername($_SESSION["user"]);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid h-5">
        <a class="navbar-brand" href="./feed.php">
            <img class="logo" src="./images/pixelab_logo.png" alt="pixelab_logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center w-100" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item align-self-md-end">
                    <form class="d-flex m-0">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    </form>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="./uploadPost.php">
                        <i class="fa fa-plus font-size-1-half" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdownMenuNotifications" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell-o font-size-1-half" aria-hidden="false"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuNotifications">
                        <li><a class="dropdown-item" href="#">Notification 1</a></li>
                        <li><a class="dropdown-item" href="#">Notification 2</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown h-5">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if (!empty($currentUser)) : ?>
                            <?php if (!empty($currentUser->getAvatar())) : ?>
                                <img src="./uploads/avatars/<?php echo $currentUser->getAvatar();?>" alt="" class="rounded-circle max-w-1-half-rem">
                            <?php else: ?>
                        <img src="./images/blank_avatar.png" alt="" class="rounded-circle max-w-1-half-rem">
                            <?php endif; ?>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="./profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="./login.php">Log out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://use.fontawesome.com/2dd2522a24.js"></script>
<script src="./scripts/bootstrap.js"></script>