<?php

include_once(__DIR__ . "/Db.php");

 Class Post {
     private $user;
     private $image;
     private $description;
     private $timestamp;
     private $likes = array();

     public function __construct($user = null, $image = null, $description = null, $timestamp = null, $likes = null)
     {
         $this->setUser($user);
         $this->setImage($image);
         $this->setDescription($description);
         $this->setTimestamp($timestamp);
         $this->setLikes($likes);
     }


     public static function fetchRecentPosts()
     {
         $conn = Db::getConnection();
         $statement = $conn->prepare("SELECT p.id, username, image, description, timestamp FROM posts p JOIN users u ON u.id = p.user_id ORDER BY timestamp DESC  LIMIT 20");
         $statement->execute();

         $posts = $statement->fetchAll();
         if (!$posts) {
             throw new Exception('There are no posts found');
         }
         $recentPosts = Array();
         $postLikes = Array();
         $i=0;
         foreach ($posts as $post) {
            $statement = $conn->prepare("SELECT username FROM post_likes pl JOIN users u ON u.id = pl.user_id WHERE post_id = :postId");
            $statement->bindValue(":postId", $post['id']);
            $statement->execute();
            $fetchedLikes = $statement->fetchAll();
            if (!empty($fetchedLikes)){
                foreach ($fetchedLikes as $fetchedLike){
                    array_push($postLikes, $fetchedLike['username']);
                }
            }

            array_push($recentPosts, new Post($post['username'], $post['image'], $post['description'], $post['timestamp'], $postLikes));
            $postLikes = Array();
         }
         return $recentPosts;

     }


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


 }