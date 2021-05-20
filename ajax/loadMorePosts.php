<?php
include_once ('../classes/Post.php');

if (!empty($_POST)) {
    session_start();

    try {
        $posts = (strpos($_SERVER['REQUEST_URI'], "explore")) ? $posts = Post::fetchRecentPosts(20, $_POST['currentAmount']) : $posts = Post::fetchRecentPostsFromFollowing(20, $_POST['currentAmount']);
        $postAmount = sizeof($posts);

        $response = [
            'status' => 'success',
            'body' => [$posts, $postAmount],
            'message' => 'Posts loaded'
        ];

        header('Content-type: application/json');
        echo json_encode($response);
    } catch (Exception $e) {
        $response = [
            'status' => 'Failed',
            'body' => 'Something went wrong.',
            'message' => $e->getMessage()
        ];
        echo json_encode($response);
    }
}


