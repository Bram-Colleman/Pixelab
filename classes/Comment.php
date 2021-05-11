<?php

include_once(__DIR__ . "/Db.php");
include_once(__DIR__ . "/User.php");

class Comment
{
    private $user_id;
    private $post_id;
    private $content;

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

    public function timeAgo($commentId) {
        date_default_timezone_set ('Europe/Brussels');

        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT timestamp FROM comments WHERE id = :id");
        $statement->bindValue(":id", $commentId);
        $statement->execute();
        $data = $statement->fetch();

        $time = strtotime($data[0]);

        $time = time() - $time;
        $time = ($time < 1) ? 1 : $time;
        $tokens = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
        }
    }
}