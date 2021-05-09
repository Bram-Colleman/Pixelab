<?php
include_once ('../classes/User.php');

if (!empty($_POST)) {
    session_start();

    try {
        $user = User::fetchUserByUsername($_POST['following']);
        $user->follow();
        $b = [  'amount' => sizeof($user->fetchFollowers()),
                'userid' => $user->getId()
        ];

        $response = [
            'status' => 'success',
            'body' => $b,
            'message' => 'User followed'
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


