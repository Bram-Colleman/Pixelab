<?php
if (!isset($_SESSION)) {
    session_start();
}
if(empty($_SESSION['email'])) {
    session_destroy();
    header("Location: login.php");
}