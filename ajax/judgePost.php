<?php
    include_once (__DIR__."/../classes/Post.php");
    $keepPost = $_POST['keepPost'];

    if(!empty($_POST)){
        if(!$keepPost == false){
            Post::deleteStrikes($_POST['postId']);
            $response = [
                'status' => 'Success',
                'message' => 'Strikes got deleted.'
            ];
        }else{
            $response = "post weg";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }