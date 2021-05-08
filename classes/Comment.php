<?php

include_once(__DIR__ . "/Db.php");
include_once(__DIR__ . "/User.php");

class Comment {
    private $user_id;
    private $post_id;
    private $content;
    private $timestamp;

    // Getters
    public function getUserId()
    {
        return $this->user_id;
    }
    public function getPostId()
    {
        return $this->post_id;
    }
    public function getContent()
    {
        return $this->content;
    }
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    // Setters
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }
    public function setPostId($post_id): void
    {
        $this->post_id = $post_id;
    }
    public function setContent($content): void
    {
        $this->content = $content;
    }
    public function setTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    // Methods
    public function save() {
        $conn = Db::getConnection();
        $userId = $this->getUserId();
        $postId = $this->getPostId();
        $content = $this->getContent();

        $statement = $conn->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (:user_id, :post_id, :content)");
        $statement->bindValue(":user_id", $userId);
        $statement->bindValue(":post_id", $postId);
        $statement->bindValue(":content", $content);
        return $statement->execute();
    }
}