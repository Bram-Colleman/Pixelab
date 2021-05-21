<?php
include_once(__DIR__ . "/../classes/Db.php");
include_once(__DIR__ . "/../classes/Comment.php");

if (!empty($_POST)) {
    session_start();

    $c = new Comment();
    $c->setPostId($_POST['postId']);
    $c->setContent($_POST['comment']);
    $c->setUserId($_SESSION['userId']);

    $c->save();

    $response = [
        'status' => 'success',
        'body' => htmlspecialchars($c->getContent()),
        'message' => 'Comment saved'
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}