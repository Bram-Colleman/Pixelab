<?php
    include_once (__DIR__."/../classes/Post.php");

    if(!empty($_POST)){
        session_start();
        if(!Post::alreadyReported($_POST['postId'], User::fetchUserByUsername($_SESSION["user"])->getId())){
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
                    'body' => $e,
                    'message' => 'Something went wrong.'
                ];
                echo json_encode($response);
            }
        }else{
            $response = [
                'status' => 'Failed',
                'body' => 'Already reported post',
                'message' => 'Something went wrong.'
            ];
            echo json_encode($response);
        }
    }