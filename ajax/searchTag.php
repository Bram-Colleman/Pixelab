<?php
    include_once (__DIR__."/../classes/Post.php");

    if(!empty($_POST)){
        $posts = Post::search($_POST['search']);
        $response = [
            'status' => 'Success',
            'body' => $_POST['search'],
            'message' => 'Looked for tag.'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    }