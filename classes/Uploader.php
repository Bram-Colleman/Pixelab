<?php

include_once(__DIR__ . "/Db.php");
include_once (__DIR__ . "/User.php");

class Uploader {

    private $target_file;
    private $imageFileType;
    private $uploadOk;
    private $target_dir;
    private $avatar;
    private $username = "";
    private $postImage;
    private $description;
    private $userId;

    public function __construct($username = "") {
        $this->setUsername($username);
    }

    public function getUsername(): string
    {
        return $this->username;
    }
    public function setUsername($username): void
    {
        $this->username = $username;
    }
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }
    public function getTargetDir()
    {
        return $this->target_dir;
    }
    public function setTargetDir($target_dir): void
    {
        $this->target_dir = $target_dir;
    }
    public function getUploadOk()
    {
        return $this->uploadOk;
    }
    public function setUploadOk($uploadOk): void
    {
        $this->uploadOk = $uploadOk;
    }
    public function getTargetFile()
    {
        return $this->target_file;
    }
    public function setTargetFile($target_file): void
    {
        $this->target_file = $target_file;
    }
    public function getImageFileType()
    {
        return $this->imageFileType;
    }
    public function setImageFileType($imageFileType): void
    {
        $this->imageFileType = $imageFileType;
    }
    public function getPostImage()
    {
        return $this->postImage;
    }
    public function setPostImage($postImage): void
    {
        $this->postImage = $postImage;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setDescription($description): void
    {
        $this->description = $description;
    }
    public function getUserId()
    {
        return $this->userId;
    }
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    public function uploadAvatar() {
        $fileName = $this->getUsername() . "_" . date('YmdHis') . ".jpg";
        $this->setTargetDir( "uploads/avatars/");
        if(strpos($_FILES["avatar"]["name"], ".jpg") || strpos($_FILES["avatar"]["name"], ".png") || strpos($_FILES["avatar"]["name"], ".jpeg") ) {
            $this->setTargetFile($this->getTargetDir() . basename($fileName));
        }
        $this->setImageFileType(strtolower(pathinfo($this->getTargetFile(), PATHINFO_EXTENSION)));
        $this->setUploadOk(0);

// Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["avatar"]["tmp_name"]);
            if($check !== false) {
                $this->setUploadOk(0);
            } else {
                $this->setUploadOk(1);
            }
        }

        if ($_FILES["avatar"]["size"] > 500000) {
            $this->setUploadOk(2);
        }

        if ($this->getImageFileType() != "jpg" && $this->getImageFileType() != "png" && $this->getImageFileType() != "jpeg") {
            $this->setUploadOk(3);
        }

        switch ($this->getUploadOk()) {
            case 0:
                if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $this->getTargetFile())) {
                    $this->setUploadOk(0);

                    $conn = Db::getConnection();
                    $statement = $conn->prepare("UPDATE users SET avatar = :avatar where username = :username");
                    $statement->bindValue(":username", $this->getUsername());
                    $statement->bindValue(":avatar", $fileName);
                    return $statement->execute();

                } else {
                    $this->setUploadOk(1);
                }
                break;
            case 1:
                $this->setUploadOk(1);
                break;
            case 2:
                $this->setUploadOk(2);
                break;
            case 3:
                $this->setUploadOk(3);
                break;
        }
        return false;
    }

    public function uploadPost($userId, $image, $description) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO posts (user_id, image, description) VALUES (:userId, :image, :description)");
        $statement->bindValue(":userId", $userId);
        $statement->bindValue(":image", $image);
        $statement->bindValue(":description", $description);
        $statement->execute();
    }
}