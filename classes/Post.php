<?php

include_once(__DIR__ . "/Db.php");
include_once(__DIR__ . "/User.php");

class Post
{
    private $user;
    private $image;
    private $description;
    private $timestamp;
    private $likes = array();
    private $comments = array();

    //constructor
    public function __construct($user = null, $image = null, $description = null, $timestamp = null, $likes = null, $comments = null)
    {
        $this->setUser($user);
        $this->setImage($image);
        $this->setDescription($description);
        $this->setTimestamp($timestamp);
        $this->setLikes($likes);
        $this->setComments($comments);
    }

    //Getters
    public function getUser()
    {
        return $this->user;
    }
    public function getImage()
    {
        return $this->image;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    public function getLikes(): array
    {
        return $this->likes;
    }
    public function getComments(): array
    {
        return $this->comments;
    }

    //Setters
    private function setUser($user): void
    {
        $this->user = $user;
    }
    private function setImage($image): void
    {
        $this->image = $image;
    }
    private function setDescription($description): void
    {
        $this->description = $description;
    }
    private function setTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp;
    }
    public function setLikes(array $likes): void
    {
        $this->likes = $likes;
    }
    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }

    //Methods
    public static function fetchRecentPosts()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT p.id, username, image, description, timestamp FROM posts p JOIN users u ON u.id = p.user_id ORDER BY timestamp DESC  LIMIT 20");
        $statement->execute();

        $posts = $statement->fetchAll();
        if (!$posts) {
            throw new Exception('There are no posts found');
        }
        $recentPosts = array();

        foreach ($posts as $post) {
            array_push($recentPosts, new Post($post['username'], $post['image'], $post['description'], $post['timestamp'],
                (empty(Post::fetchLikes($post['id']))) ? array() : Post::fetchLikes($post['id']), (empty(Post::fetchComments($post['id']))) ? array() : Post::fetchComments($post['id'])));
        }
        return $recentPosts;

    }
    public static function fetchLikes($postId)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT username FROM post_likes pl JOIN users u ON u.id = pl.user_id WHERE post_id = :postId");
        $statement->bindValue(":postId", $postId);
        $statement->execute();
        $fetchedLikes = $statement->fetchAll();
        $postLikes = array();
        if (!empty($fetchedLikes)) {
            foreach ($fetchedLikes as $fetchedLike) {
                array_push($postLikes, $fetchedLike['username']);
            }
        }
        return $postLikes;
    }
    public static function fetchComments($postId)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT u.username, c.content FROM comments c JOIN users u ON u.id = c.user_id WHERE c.post_id = :postId");
        $statement->bindValue(":postId", $postId);
        $statement->execute();
        $fetchedComments = $statement->fetchAll();
        $postComments = array();
        if (!empty($fetchedComments)) {
            foreach ($fetchedComments as $fetchedComment) {
                array_push($postComments, array("username" => $fetchedComment['username'], "content" => $fetchedComment['content']));
            }
        }
        return $postComments;
    }
    public static function fetchPostsByUserId($userId)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM posts WHERE user_id = :userId ORDER BY timestamp DESC");
        $statement->bindValue(":userId", $userId);
        $statement->execute();

        $posts = $statement->fetchAll();
        if (!$posts) {
            throw new Exception('Something went wrong!');
        }
        $fetchedPosts = array();

        foreach ($posts as $post) {
            array_push($fetchedPosts, new Post($post['id'], $post['image'], $post['description'], $post['timestamp'],
                (empty(Post::fetchLikes($post['id']))) ? array() : Post::fetchLikes($post['id']), (empty(Post::fetchComments($post['id']))) ? array() : Post::fetchComments($post['id'])));
        }
        return $fetchedPosts;
    }
    public static function uploadPost($email, $description)
    {

        $fileName = $_SESSION["user"] . "_" . date('YmdHis') . ".jpg";
        $targetDir = "uploads/posts/";
        $targetFile = $targetDir . basename($fileName);
        //$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if ($_FILES["file"]["error"] == 4) {
            //means there is no file uploaded
            throw new Exception("This is not an image");
        }

        move_uploaded_file($_FILES["postImage"]["tmp_name"], $targetFile);

        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO posts (user_id, image, description) VALUES (:userId, :image, :description)");
        $statement->bindValue(":userId", User::fetchUserByUsername($_SESSION["user"])->getId());
        $statement->bindValue(":image", $fileName);
        $statement->bindValue(":description", $description);
        $statement->execute();
    }

}