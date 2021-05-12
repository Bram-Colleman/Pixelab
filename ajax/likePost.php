<?php
include_once ('../classes/Post.php');

if (!empty($_POST)) {
    session_start();

    try {
        $post = Post::fetchPostById($_POST['postId']);
        ($_POST['isLiked'] == "1")? $post->unlike() : $post->like();

        $b = [  'amount' => sizeof(Post::fetchLikes($post->getId())),
                'postid' => $post->getId()
        ];

        $response = [
            'status' => 'success',
            'body' => $b,
            'message' => ($_POST['isLiked'] == "1") ? 'Post liked' : 'Post unliked'
        ];

        header('Content-type: application/json');
        echo json_encode($response);
    } catch (Exception $e) {
        $response = [
            'status' => 'Failed',
            'body' => 'Something went wrong.',
            'message' => 'Something went wrong.'
        ];
        echo json_encode($response);
    }


}


