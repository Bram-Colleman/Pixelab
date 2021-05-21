<?php

include_once(__DIR__ . "/Db.php");

class User
{
    private $id;
    private $username;
    private $email;
    private $bio;
    private $avatar;
    private $password;
    private $followers = array();
    private $target_file;
    private $target_dir;
    private $imageFileType;

    // Constructor
    public function __construct($id = null, $username = null, $email = null, $bio = null, $avatar = null, $password = null, $followers = array())
    {
        $this->setId($id);
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setBio($bio);
        $this->setAvatar($avatar);
        $this->setPassword($password);
        $this->setFollowers($followers);
    }

    // Getters
    public function getId()
    {
        return $this->id;
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
    public function getFollowers()
    {
        return $this->followers;
    }
    public function getTargetFile()
    {
        return $this->target_file;
    }
    public function getTargetDir()
    {
        return $this->target_dir;
    }
    public function getImageFileType()
    {
        return $this->imageFileType;
    }

    // Setters
    private function setId($id): void
    {
        $this->id = $id;
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
    private function setFollowers($followers): void
    {
        $this->followers = $followers;
    }
    private function setTargetFile($target_file): void
    {
        $this->target_file = $target_file;
    }
    private function setTargetDir($target_dir): void
    {
        $this->target_dir = $target_dir;
    }
    private function setImageFileType($imageFileType): void
    {
        $this->imageFileType = $imageFileType;
    }

    // Methods
    public static function fetchUserByEmail($email)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();

        $user = $statement->fetch();
        if (!$user) {
            throw new Exception('This user does not exist');
        }
        $newUser = new User($user['id'], $user['username'], $user['email'], $user['bio'], $user['avatar'], $user['password']);
        $newUser->setFollowers($newUser->fetchFollowers());
        return $newUser;
    }
    public static function fetchUserByUsername($username)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $statement->bindValue(":username", $username);
        $statement->execute();

        $user = $statement->fetch();
        if (!$user) {
            throw new Exception('This user does not exist');
        }
        $newUser = new User($user['id'], $user['username'], $user['email'], $user['bio'], $user['avatar'], $user['password']);
        $newUser->setFollowers($newUser->fetchFollowers());
        return $newUser;
    }
    public function fetchFollowers() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT username FROM followers f JOIN users u ON u.id = follower_id WHERE following_id = :followingId");
        $statement->bindValue(":followingId", $this->getId());
        $statement->execute();
        $fetchedFollowers = $statement->fetchAll();
        $followers = array();
        if (!empty($fetchedFollowers)) {
            foreach ($fetchedFollowers as $fetchedFollower) {
                array_push($followers, $fetchedFollower['username']);
            }
        }
        return $followers;
    }
    public function fetchFollowing() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT username FROM followers f JOIN users u ON u.id = follower_id WHERE follower_id = :followerId");
        $statement->bindValue(":followerId", $this->getId());
        $statement->execute();
        $fetchedFollowings = $statement->fetchAll();
        $followings = array();
        if (!empty($fetchedFollowings)) {
            foreach ($fetchedFollowings as $fetchedFollowing) {
                array_push($followings, $fetchedFollowing['username']);
            }
        }
        return $followings;
    }
    private static function getRole($id) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select r.*
        from users u
        inner join (select id, name from user_roles) r
        on u.user_role = r.id
        WHERE u.id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $result = $statement->fetch();
        return $result['name'];
    }
    public function updateUser($username, $bio, $email, $currentPassword, $newPassword)
    {
        if (password_verify($currentPassword, $this->getPassword())){
            $conn = Db::getConnection();
            $statement = $conn->prepare("SELECT COUNT(*) FROM users WHERE id != :id AND (email = :email OR username = :username)");
            $statement->bindValue(":id", $this->getId());
            $statement->bindValue(":email", $email);
            $statement->bindValue(":username", $username);
            $statement->execute();
            $check = $statement->fetch()['COUNT(*)'];

            if($_FILES['avatar']['size'] > 700000) {
                throw new Exception("File is too large, it can't be bigger than 700KB");
            } else {
                if ($_FILES['avatar']['size'] != 0 && $_FILES['avatar']['error'] == 0)
                {
                    $fileName = $this->getUsername() . "_" . date('YmdHis') . ".jpg";
                    $this->setTargetDir( "uploads/avatars/");
                    $this->setImageFileType(strtolower(pathinfo($this->getTargetFile(), PATHINFO_EXTENSION)));

                    $this->setTargetFile($this->getTargetDir() . basename($fileName));

                    move_uploaded_file($_FILES["avatar"]["tmp_name"], $this->getTargetFile());
                    if (!empty($this->getAvatar())){
                        unlink("uploads/avatars/" . $this->getAvatar());
                    }
                }
            }

            if (!empty($newPassword)) {
                $options = [
                    'cost' => 12,
                ];
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT, $options);

                $statement = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
                $statement->bindValue(":email", $this->getEmail());
                $statement->bindValue(":password", $hashedPassword);
                $statement->execute();
            }

            if ($this->checkIfUserExists($email, $username) <= 1) {
                $statement = $conn->prepare("UPDATE users SET username = :username, bio = :bio, email = :newEmail, avatar = :avatar, user_role = user_role WHERE email = :email");
                $statement->bindValue(":username", $username);
                $statement->bindValue(":bio", $bio);
                $statement->bindValue(":newEmail", $email);
                $statement->bindValue(":email", $this->getEmail());
                $statement->bindValue(":avatar", (isset($fileName) ? $fileName : $this->getAvatar()));
                $statement->execute();

                session_destroy();
                session_start();
                $_SESSION["user"] = $username;
                $_SESSION["email"] = $email;
                $_SESSION["userId"] = $this->getId();
                $_SESSION['userRole'] = User::getRole($this->getId());
                header('Location: index.php');
            }
        }
    }
    public function deleteAvatar() {
        $conn = Db::getConnection();

        unlink("uploads/avatars/" . $this->getAvatar());

        $statement = $conn->prepare("UPDATE users SET avatar = null WHERE id = :id");
        $statement->bindValue(":id", $this->getId());
        $statement->execute();

        header('Location: index.php');
    }
    public static function login($email, $password){

        $conn = Db::getConnection();
        $statement = $conn->prepare("select u.*, u.id as userId, r.*
        from users u
        inner join (select id, name as userRole from user_roles) r
        on u.user_role = r.id
        WHERE u.email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();
        // get user connected to email
        $user = $statement->fetch();
        if(!$user){
            throw new Exception('This user does not exist');
        }

        //verify password
        $hash = $user["password"];
        if(password_verify($password, $hash)){
            // login
            session_start();
            $_SESSION["user"] = $user['username'];
            $_SESSION["email"] = $email;
            $_SESSION["userId"] = $user['userId'];
            $_SESSION["userRole"] = $user['userRole'];
            header("Location: index.php");
        }else{
            throw new Exception('Incorrect password');
        }

    }
    public function register() {
        $conn = Db::getConnection();
        $username = $this->getUsername();
        $email = $this->getEmail();
        $password = $this->getPassword();

        if($this->checkIfUserExists($email, $username) == 0) {
            $statement = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $options = [
                'cost' => 12,
            ];
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT, $options);

            $statement->bindValue(":username", $username);
            $statement->bindValue(":email", $email);
            $statement->bindValue(":password", $hashedPassword);

            header("Location: login.php");

            $result = $statement->execute();
            return $result;
        } else {
            throw new Exception("User already exists");
        }
    }
    public function checkIfUserExists($email, $username)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT COUNT(*) FROM users WHERE (email = :email OR username = :username)");
        $statement->bindValue(":email", $email);
        $statement->bindValue(":username", $username);
        $statement->execute();
        $check = $statement->fetch()['COUNT(*)'];
        return $check;
    }
    public function follow() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO followers (follower_id, following_id) VALUES (:followerId, :followingId)");
        $statement->bindValue(":followerId", User::fetchUserByUsername($_SESSION["user"])->getId());
        $statement->bindValue(":followingId", $this->getId());
        $result = $statement->execute();
        return $result;
    }
    public function unfollow() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("DELETE FROM followers WHERE following_id = :followingId AND follower_id = :followerId");
        $statement->bindValue(":followingId", $this->getId());
        $statement->bindValue(":followerId", User::fetchUserByUsername($_SESSION['user'])->getId());
        $result = $statement->execute();
        return $result;
    }
    public static function hasPosts($userId) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT COUNT(*) FROM posts WHERE user_id = :userId");
        $statement->bindValue(":userId", $userId);
        $statement->execute();
        $result = $statement->fetch();
        return $result[0] != 0;
    }
}