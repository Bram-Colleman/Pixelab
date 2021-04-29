<?php

include_once(__DIR__ . "/Db.php");

class User
{
    private $username;
    private $email;
    private $bio;
    private $avatar;
    private $password;

    public function __construct($username = null, $email = null, $bio = null, $avatar = null, $password = null)
    {
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setBio($bio);
        $this->setAvatar($avatar);
        $this->setPassword($password);
    }

    public static function fetchUser($email)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();

        $user = $statement->fetch();
        if (!$user) {
            throw new Exception('This user does not exist');
        }
        return new User($user['username'], $user['email'], $user['bio'], $user['avatar'], $user['password']);
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getBio()
    {
        return $this->bio;
    }
    public function getAvatar()
    {
        return $this->avatar;
    }
    public function getPassword()
    {
        return $this->password;
    }


    public function updateUser($username, $bio, $email, $password)
    {
        if ($password === $this->getPassword()){
            $conn = Db::getConnection();
            $statement = $conn->prepare("UPDATE users SET username = :username, bio = :bio, email = :newEmail WHERE email = :email");
            $statement->bindValue(":username", $username);
            $statement->bindValue(":bio", $bio);
            $statement->bindValue(":newEmail", $email);
            $statement->bindValue(":email", $this->getEmail());
            $statement->execute();

            header('Location: feed.php');

        }
    }
    private function setUsername($username): void
    {
        $this->username = $username;
    }
    private function setEmail($email): void
    {
        $this->email = $email;
    }
    private function setBio($bio): void
    {
        $this->bio = $bio;
    }
    private function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }
    private function setPassword($password): void
    {
        $this->password = $password;
    }

    public function login($email, $password)
    {
        function canLogin($email, $password)
        {
            $conn = Db::getConnection();
            $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $statement->bindValue(":email", $email);
            $statement->execute();
            // get user connected to email
            $user = $statement->fetch();
            if (!$user) {
                throw new Exception('This user does not exist');
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
            if ($password === $user["password"]) {
                return true;
            } else {
                return false;
            }
            // --------------------------------------
        }

        if (canLogin($email, $password)) {
            $user = $this::fetchUser($email);
            // login
            session_start();
            $_SESSION['user'] = $user->getUsername();
            $_SESSION["email"] = $email;
            header("Location: feed.php");
        } else {
            throw new Exception('Incorrect password');
        }
    }

}