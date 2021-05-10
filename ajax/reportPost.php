<?php
    include_once (__DIR__."/../classes/Post.php");

    if(!empty($_POST)){
        session_start();
        try{
            $report = Post::fetchPostById($_POST['postId']);
            $report->reportPost();
            $data = [
                'postid' => $report->getId()
            ];

            $response = [
                'status' => 'success',
                'body' => $data,
                'message' => 'Reported post'
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        }catch (Exception $e) {
            $response = [
                'status' => 'Failed',
                'body' => 'Something went wrong.',
                'message' => 'Something went wrong.'
            ];
            echo json_encode($response);
        }
    }