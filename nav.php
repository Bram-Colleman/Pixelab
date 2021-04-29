<?php
    session_start();
    include_once(__DIR__."/classes/User.php");
    try {
        $user = User::fetchUser($_SESSION['email']);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid" style="height: 5%">
        <a class="navbar-brand" href="./feed.php">Pixelab</a>
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
                    <a class="nav-link" href="#"><i class="fa fa-bell-o" aria-hidden="true"
                                                    style="font-size: 1.5rem"></i></a>
                </li>
                <li class="nav-item dropdown" style="height: 5%">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if (!empty($user)) : ?>
                            <?php if (!empty($user->getAvatar())) : ?>
                                <img src="./uploads/avatars/<?php echo $user->getAvatar();?>" alt="" class="rounded-circle" style="max-width: 1.5rem">
                            <?php else: ?>
                        <img src="./images/blank_avatar.png" alt="" class="rounded-circle" style="max-width: 1.5rem">
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
<script src="scripts/bootstrap.js"></script>