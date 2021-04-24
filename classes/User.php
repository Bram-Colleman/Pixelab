<?php

include_once(__DIR__."/Db.php");

class User {

    public function login($email, $password){
        function canLogin($email, $password){
            $conn = Db::getConnection();
            $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $statement->bindValue(":email", $email);
            $statement->execute();
            // get user connected to email
            $user = $statement->fetch();
            if(!$user){
                return false;
            }
            //verify password
            
            // WHEN SIGNUP IS ADD, USE THIS CODE
            /*
            $hash =  $user["password"];
            if(password_verify($password, $hash)){
                return true;
            }else{
                return false;
            }
            */
            // --------------------------------------

            // TO TEST DUMMY DATA, I ADDED THIS CODE
            if($password === $user["password"]){
                return true;
            }else{
                return false;
            }
            // --------------------------------------
        }

        if(canLogin($email, $password)){
            echo "we can login";
            // login
            session_start();
            $_SESSION["email"] = $email;
            header("Location: profile.php");
        }else{
            //error
        }
    }

}