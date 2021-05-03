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
    private $target_file;
    private $imageFileType;
    private $uploadOk;
    private $target_dir;

    public function __construct($id = null, $username = null, $email = null, $bio = null, $avatar = null, $password = null)
    {
        $this->setId($id);
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setBio($bio);
        $this->setAvatar($avatar);
        $this->setPassword($password);
    }

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
        return new User($user['id'], $user['username'], $user['email'], $user['bio'], $user['avatar'], $user['password']);
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
        return new User($user['id'], $user['username'], $user['email'], $user['bio'], $user['avatar'], $user['password']);
    }
    public static function fetchUserByUserId($userId)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $statement->bindValue(":id", $userId);
        $statement->execute();

        $user = $statement->fetch();
        if (!$user) {
            throw new Exception('This user does not exist');
        }
        return new User($user['id'], $user['username'], $user['email'], $user['bio'], $user['avatar'], $user['password']);
    }

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
    public function getTargetDir()
    {
        return $this->target_dir;
    }
    public function getUploadOk()
    {
        return $this->uploadOk;
    }
    public function getTargetFile()
    {
        return $this->target_file;
    }
    public function getImageFileType()
    {
        return $this->imageFileType;
    }


    public function updateUser($username, $bio, $email, $password)
    {
        if (password_verify($password, $this->getPassword())){
            $conn = Db::getConnection();
            $statement = $conn->prepare("SELECT COUNT(*) FROM users WHERE id != :id AND (email = :email OR username = :username)");
            $statement->bindValue(":id", $this->getId());
            $statement->bindValue(":email", $email);
            $statement->bindValue(":username", $username);
            $statement->execute();
            $check = $statement->fetch()['COUNT(*)'];

            if($_FILES["file"]["error"] == 4) {
//means there is no file uploaded
                throw new Exception("This is not an image");
            }

            $fileName = $this->getUsername() . "_" . date('YmdHis') . ".jpg";
            $this->setTargetDir( "uploads/avatars/");
            $this->setImageFileType(strtolower(pathinfo($this->getTargetFile(), PATHINFO_EXTENSION)));

            $this->setTargetFile($this->getTargetDir() . basename($fileName));

            move_uploaded_file($_FILES["avatar"]["tmp_name"], $this->getTargetFile());

            if ($check == 0) {
                $statement = $conn->prepare("UPDATE users SET username = :username, bio = :bio, email = :newEmail, avatar = :avatar WHERE email = :email");
                $statement->bindValue(":username", $username);
                $statement->bindValue(":bio", $bio);
                $statement->bindValue(":newEmail", $email);
                $statement->bindValue(":email", $this->getEmail());
                $statement->bindValue(":avatar", $fileName);
                $statement->execute();
                header('Location: feed.php');
            }
        }
    }
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
        $options = [
            'cost' => 12,
        ];
        $password = password_hash($password, PASSWORD_DEFAULT, $options);

        $this->password = $password;
    }
    public function setTargetDir($target_dir): void
    {
        $this->target_dir = $target_dir;
    }
    public function setUploadOk($uploadOk): void
    {
        $this->uploadOk = $uploadOk;
    }
    public function setTargetFile($target_file): void
    {
        $this->target_file = $target_file;
    }
    public function setImageFileType($imageFileType): void
    {
        $this->imageFileType = $imageFileType;
    }

    public static function login($email, $password){

        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
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
            header("Location: feed.php");
        }else{
            throw new Exception('Incorrect password');
        }

    }

    public function register() {
        $conn = Db::getConnection();

        $statement = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

        $username = $this->getUsername();
        $email = $this->getEmail();
        $password = $this->getPassword();

        $statement->bindValue(":username", $username);
        $statement->bindValue(":email", $email);
        $statement->bindValue(":password", $password);

        header("Location: login.php");

        $result = $statement->execute();
        return $result;
    }

//    public function uploadAvatar() {
//        $fileName = $this->getUsername() . "_" . date('YmdHis') . ".jpg";
//        $this->setTargetDir( "uploads/avatars/");
//        $this->setImageFileType(strtolower(pathinfo($this->getTargetFile(), PATHINFO_EXTENSION)));
//        $this->setUploadOk(0);
//        if(strpos($_FILES["avatar"]["name"], ".jpg") || strpos($_FILES["avatar"]["name"], ".png") || strpos($_FILES["avatar"]["name"], ".jpeg") ) {
//            $this->setTargetFile($this->getTargetDir() . basename($fileName));
//        }

//        if(isset($_POST["submit"])) {
//            $check = getimagesize($_FILES["avatar"]["tmp_name"]);
//            if($check !== false) {
//                $this->setUploadOk(0);
//            } else {
//                $this->setUploadOk(1);
//            }
//        }

//        if ($_FILES["avatar"]["size"] > 2000000) {
//            $this->setUploadOk(2);
//        }
//
//        if ($this->getImageFileType() != "jpg" && $this->getImageFileType() != "png" && $this->getImageFileType() != "jpeg") {
//            $this->setUploadOk(3);
//        }
//
//        switch ($this->getUploadOk()) {
//            case 0:
//                if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $this->getTargetFile())) {
//                    $this->setUploadOk(0);
//
//                    $conn = Db::getConnection();
//                    $statement = $conn->prepare("UPDATE users SET avatar = :avatar where username = :username");
//                    $statement->bindValue(":username", $this->getUsername());
//                    $statement->bindValue(":avatar", $fileName);
//                    return $statement->execute();
//
//                } else {
//                    $this->setUploadOk(1);
//                }
//                break;
//            case 1:
//                $this->setUploadOk(1);
//                break;
//            case 2:
//                $this->setUploadOk(2);
//                break;
//            case 3:
//                $this->setUploadOk(3);
//                break;
//        }
//        return false;
//    }

}