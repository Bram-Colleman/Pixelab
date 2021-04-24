<?php

include_once(__DIR__."/Db.php");

class User {

    public function canLogin($email, $password){
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();
        // get user connected to email
        $user = $statement->fetch();
        echo $user["password"];
        die();
    }

}