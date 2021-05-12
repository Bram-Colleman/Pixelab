<?php
    include_once (__DIR__."/../classes/Post.php");
    $keepPost = $_POST['keepPost'];

    if(!empty($_POST)){
        if(!$keepPost == false){
            $response = "post behouden";
        }else{
            $response = "post weg";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }