<?php

include_once(__DIR__ . "/Db.php");

class User
{
    private $username;
    private $email;
    private $bio;
    private $avatar;

    public function __construct($username = null, $email = null, $bio = null, $avatar = null)
    {
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setBio($bio);
        $this->setAvatar($avatar);
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
        return new User($user['username'], $user['email'], $user['bio'], $user['avatar']);
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

    public function updateUser($username, $bio, $email)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("UPDATE users SET username = :username, bio = :bio, email = :newEmail WHERE email = :email");
        $statement->bindValue(":username", $username);
        $statement->bindValue(":bio", $bio);
        $statement->bindValue(":newEmail", $email);
        $statement->bindValue(":email", $this->getEmail());
        $statement->execute();

    }
    public function setUsername($username): void
    {
        $this->username = $username;
    }
    public function setEmail($email): void
    {
        $this->email = $email;
    }
    public function setBio($bio): void
    {
        $this->bio = $bio;
    }
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
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
            // login
            session_start();
            $_SESSION["email"] = $email;
            header("Location: feed.php");
        } else {
            throw new Exception('Incorrect password');
        }
    }

}