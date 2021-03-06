<?php

include_once(__DIR__ . "/Db.php");
include_once(__DIR__ . "/User.php");
include_once (__DIR__ . "/Comment.php");

ini_set("display_errors", false);

class Post
{
    private $id;
    private $user;
    private $image;
    private $description;
    private $timestamp;
    private $likes = array();
    private $comments = array();
    private $filter;


    // Constructor
    public function __construct($id = null, $user = null, $location = null, $image = null, $description = null, $timestamp = null, $likes = array(), $comments = array(), $filter = null)
    {
        $this->setId($id);
        $this->setUser($user);
        $this->setLocation($location);
        $this->setImage($image);
        $this->setDescription($description);
        $this->setTimestamp($timestamp);
        $this->setLikes($likes);
        $this->setComments($comments);
        $this->setFilter($filter);
    }

    // Getters
    public function getUser()
    {
        return $this->user;
    }
    public function getLocation()
    {
        return $this->location;
    }
    public function getImage()
    {
        return $this->image;
    }
    public function getId()
    {
        return $this->id;
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
    public function getFilter()
    {
        return $this->filter;
    }

    // Setters
    private function setId($id): void
    {
        $this->id = $id;
    }
    private function setUser($user): void
    {
        $this->user = $user;
    }
    private function setLocation($location): void
    {
        $this->location = $location;
    }
    private function setImage($image): void
    {
        $this->image = $image;
    }
    private function setDescription($description): void
    {
        if (!empty($description)) {
            $this->description = $description;
        }
        else {
            throw new Exception("Description can not be empty!");
        }
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
    public function setFilter($filter): void
    {
        $this->filter = $filter;

    }

    // Methods
    public static function fetchRecentPosts($limit, $offset)

    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT p.id, username, image, filter, description, timestamp, location FROM posts p JOIN users u ON u.id = p.user_id WHERE p.user_id != :sessionUserId ORDER BY timestamp DESC LIMIT $limit OFFSET $offset");
        $statement->bindValue(":sessionUserId", $_SESSION['userId']);
        $statement->execute();
        $posts = $statement->fetchAll();
        if (!$posts) {
            throw new Exception('There are no posts found');
        }
        $recentPosts = array();

        if ($offset === 0 ){
            foreach ($posts as $post) {   
                if(Post::postReportCount($post['id'])<3 && !in_array($post['username'], User::fetchFollowingUsernames($_SESSION['userId']))){
                    array_push($recentPosts, new Post($post['id'],$post['username'], $post['location'], $post['image'], $post['description'], $post['timestamp'],
                        (empty(Post::fetchLikes($post['id']))) ? array() : Post::fetchLikes($post['id']), (empty(Post::fetchComments($post['id']))) ? array() : Post::fetchComments($post['id']), $post['filter']));
                }
            }
            return $recentPosts;
        } else {
            foreach ($posts as $post) {
                if(Post::postReportCount($post['id'])<3 && !in_array($post['username'], User::fetchFollowingUsernames($_SESSION['userId']))){
                    $newPost = new Post($post['id'],$post['username'], $post['location'], $post['image'], $post['description'], $post['timestamp'],
                        (empty(Post::fetchLikes($post['id']))) ? array() : Post::fetchLikes($post['id']), (empty(Post::fetchComments($post['id']))) ? array() : Post::fetchComments($post['id']), $post['filter']);
                    array_push($recentPosts, $newPost->toArray());
                }
            }
            return $recentPosts;
        }

    }
    public static function fetchRecentPostsFromFollowing($limit, $offset)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT p.id, username, image, filter, description, timestamp, location, p.user_id 
                                                FROM posts p 
                                                JOIN users u ON u.id = p.user_id 
                                                WHERE p.user_id IN ( 
                                                    SELECT f.following_id 
                                                    FROM followers f 
                                                    JOIN users u ON f.follower_id = u.id 
                                                    WHERE u.id = :userId) 
                                                OR p.user_id = :userId
                                                ORDER BY timestamp DESC LIMIT $limit OFFSET $offset");
        $statement->bindValue(":userId", $_SESSION['userId']);
        $statement->execute();
        $posts = $statement->fetchAll();
        if (!$posts) {
            throw new Exception('There are no more posts.');
        }
        $recentPosts = array();

        if ($offset === 0 ){
            foreach ($posts as $post) {
                if(Post::postReportCount($post['id'])<3){
                    array_push($recentPosts, new Post($post['id'],$post['username'], $post['location'], $post['image'], $post['description'], $post['timestamp'],
                        (empty(Post::fetchLikes($post['id']))) ? array() : Post::fetchLikes($post['id']), (empty(Post::fetchComments($post['id']))) ? array() : Post::fetchComments($post['id']), $post['filter']));
                }
            }
            return $recentPosts;
        } else {
            foreach ($posts as $post) {
                if(Post::postReportCount($post['id'])<3){
                    $newPost = new Post($post['id'],$post['username'],  $post['location'], $post['image'], $post['description'], $post['timestamp'],
                        (empty(Post::fetchLikes($post['id']))) ? array() : Post::fetchLikes($post['id']), (empty(Post::fetchComments($post['id']))) ? array() : Post::fetchComments($post['id']), $post['filter']);
                    array_push($recentPosts, $newPost->toArray());
                }
            }
            return $recentPosts;
        }

    }
    public static function fetchLikes($postId): array
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
    public static function fetchComments($postId): array
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT u.username, c.content, c.id FROM comments c JOIN users u ON u.id = c.user_id WHERE c.post_id = :postId");
        $statement->bindValue(":postId", $postId);
        $statement->execute();
        $fetchedComments = $statement->fetchAll();
        $postComments = array();
        if (!empty($fetchedComments)) {
            foreach ($fetchedComments as $fetchedComment) {
                array_push($postComments, array("username" => $fetchedComment['username'], "content" => $fetchedComment['content'], "id" => $fetchedComment['id']));
            }
        }
        return $postComments;
    }
    public static function fetchPostsByUserId($userId): array
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT p.id, username, image, filter, description, timestamp, location FROM posts p JOIN users u ON u.id = p.user_id WHERE user_id = :userId ORDER BY timestamp DESC");
        $statement->bindValue(":userId", $userId);
        $statement->execute();

        $posts = $statement->fetchAll();
        if (!$posts) {
            throw new Exception('Something went wrong!');
        }
        $fetchedPosts = array();

        foreach ($posts as $post) {
            if(Post::postReportCount($post['id'])<3){
                array_push($fetchedPosts, new Post($post['id'], $post['username'], $post['location'], $post['image'], $post['description'], $post['timestamp'],
                (empty(Post::fetchLikes($post['id']))) ? array() : Post::fetchLikes($post['id']), (empty(Post::fetchComments($post['id']))) ? array() : Post::fetchComments($post['id']), $post['filter']));
            }
        }
        return $fetchedPosts;
    }
    public static function fetchPostById($id): Post
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT p.id, username, location, image, filter, description, timestamp FROM posts p JOIN users u ON u.id = p.user_id WHERE p.id = :postId");
        $statement->bindValue(":postId", $id);
        $statement->execute();

        $post = $statement->fetch();
        if (!$post) {
            throw new Exception('Something went wrong!');
        }

        return new Post($post['id'],
            $post['username'],
            $post['location'],
            $post['image'],
            $post['description'],
            $post['timestamp'],
            (empty(Post::fetchLikes($post['id']))) ? array() : Post::fetchLikes($post['id']),
            (empty(Post::fetchComments($post['id']))) ? array() : Post::fetchComments($post['id']), $post['filter']);
    }
    public static function uploadPost($description, $filter, $longitude, $latitude): void // SHOULD CHECK IF IMAGE FILE SIZE IS LARGER THAN 500000 (500kb)
    {

        $fileName = $_SESSION["user"] . "_" . date('YmdHis') . ".jpg";
        $targetDir = "uploads/posts/";
        $targetFile = $targetDir . basename($fileName);
        //$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if ($_FILES["postImage"]["size"] > 700000) {
            throw new Exception("File is too large, it can't be bigger than 700KB");
        } else {
            move_uploaded_file($_FILES["postImage"]["tmp_name"], $targetFile);

            $conn = Db::getConnection();
            $statement = $conn->prepare("INSERT INTO posts (user_id, image, description, filter, location) VALUES (:userId, :image, :description, :filter, :location)");
            $statement->bindValue(":userId", User::fetchUserByUsername($_SESSION["user"])->getId());
            $statement->bindValue(":image", $fileName);
            $statement->bindValue(":description",$description);
            $statement->bindValue(":filter", $filter);
            $statement->bindValue(":location", Post::findLocation($longitude, $latitude));
            $statement->execute();

            print_r(Post::findLocation($longitude, $latitude));
        }
    }
    private static function findLocation($long, $lat){
        $key = "0f79d74a19c5eec308a2580a7b53794c";
        $searchQuery = $lat.','.$long;

        $buildQuery = http_build_query([
        'access_key' => $key,
        'query' => $searchQuery
        ]);

        $ch = curl_init(sprintf('%s?%s', 'http://api.positionstack.com/v1/reverse', $buildQuery));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        return $result['data'][0]['administrative_area'].", ".$result['data'][0]['region'];
    }
    public function like(): bool
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO post_likes (user_id, post_id) VALUES (:userId, :postId)");
        $statement->bindValue(":userId", User::fetchUserByUsername($_SESSION["user"])->getId());
        $statement->bindValue(":postId", $this->getId());
        $result = $statement->execute();
        return $result;
    }
    public function unlike(): bool
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("DELETE FROM post_likes WHERE user_id = :userId AND post_id = :postId ");
        $statement->bindValue(":userId", User::fetchUserByUsername($_SESSION["user"])->getId());
        $statement->bindValue(":postId", $this->getId());
        $result = $statement->execute();
        return $result;
    }
    public static function search($searchText): array
    {
        if(!strpos($_SERVER['REQUEST_URI'], "explore")){
            header("Location: explore.php?search=".$searchText);
        }
        if(substr($searchText, 0, 1)=="@"){
            $splitString = explode(" ", $searchText);
            $userTag = substr($splitString[0], 1);
            $conn = Db::getConnection();
            $statement = $conn->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON u.id = p.user_id WHERE u.username = :username ORDER BY timestamp DESC");
            $statement->bindValue(":username", $userTag);
            $statement->execute();
            return Post::loadPosts($statement);
        }else if(substr($searchText, 0, 4)=="loc:"){
            $splitString = explode("loc:", $searchText);
            $location = $splitString[1];
            $conn = Db::getConnection();
            $statement = $conn->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON u.id = p.user_id WHERE p.location = :location ORDER BY timestamp DESC");
            $statement->bindValue(":location", $location);
            $statement->execute();
            return Post::loadPosts($statement);
        }else{
            $conn = Db::getConnection();
            $statement = $conn->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON u.id = p.user_id WHERE description LIKE CONCAT('%', :searchText, '%') ORDER BY timestamp DESC");
            $statement->bindValue(":searchText", $searchText);
            $statement->execute();
            return Post::loadPosts($statement);
        }

    }
    public static function interactiveDescription($description){
        $description=htmlspecialchars($description);
        // Pieces to array, split on #
        $descriptionPieces = explode("#", $description);
        for($i=1; $i<count($descriptionPieces); $i++){
            // If there is a space after the hashtag, delete it
            if(substr($descriptionPieces[$i], -1)==" "){
                $descriptionPieces[$i] = substr($descriptionPieces[$i], -strlen($descriptionPieces[$i]), -1);
            }
            // Put hashtag in front of hashtag
            $descriptionPieces[$i] = '#'.$descriptionPieces[$i];
        }
        $descriptionText = array();
        for($i=1; $i<count($descriptionPieces); $i++){
            // If the tag has a space
            if(strpos($descriptionPieces[$i], " ")){
                // Split on the space
                $tagPieces = explode(" ", $descriptionPieces[$i]);
                // Push to array
                for($c=0; $c<count($tagPieces); $c++){
                    if(substr($tagPieces[$c], 0, 1)=="#"){
                        array_push($descriptionText, '<a href="explore.php?search='.urlencode($tagPieces[$c]).'" class="btn-tag">'.$tagPieces[$c].'</a>');
                    }else{
                        array_push($descriptionText, $tagPieces[$c]);
                    }
                }
            }else{
                array_push($descriptionText, '<a href="explore.php?search='.urlencode($descriptionPieces[$i]).'" class="btn-tag">'.$descriptionPieces[$i].'</a>');
            }
        }
        //var_dump($text);
        $finalDescription = $descriptionPieces[0].implode(" ", $descriptionText);
        return $finalDescription;
    }
    public function postedTimeAgo($postId) {
        date_default_timezone_set('Europe/Brussels');

        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT timestamp FROM posts WHERE id = :id");
        $statement->bindValue(":id", $postId);
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
    public function reportPost(){
        $userId = User::fetchUserByUsername($_SESSION["user"])->getId();
        $postId = $this->getId();

        
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO post_strikes (user_id, post_id) VALUES (:userId, :postId)");
        $statement->bindValue(":userId", $userId);
        $statement->bindValue(":postId", $postId);
        $result = $statement->execute();
        return $result;
        
    }
    public static function alreadyReported($postId, $userId){
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM `post_strikes` WHERE post_id = :postId AND user_id = :userId");
        $statement->bindValue(":postId", $postId, PDO::PARAM_INT);
        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);
        $statement->execute();
        //return $result;
        $reports = $statement->fetchAll();
        if (!$reports) {
            return false;
        }else{
            return true;
        }
    }
    private static function postReportCount($postId){
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT COUNT(*) AS amount FROM `post_strikes` WHERE post_id = :postId");
        $statement->bindValue(":postId", $postId);
        $statement->execute();
        $report = $statement->fetch();

        return $report["amount"];
    }
    public static function loadReportedPosts(){
        $conn = Db::getConnection();
        $statement = $conn->prepare("select p.*, u.*
        from posts p
        inner join (select id as userId, username from users) u
        on p.user_id = u.userId");
        $statement->execute();
        $posts = $statement->fetchAll();
        if (!$posts) {
            throw new Exception('There are no posts found');
        }
        $reportedPosts = array();

        foreach ($posts as $post) {   
            $reportCount = Post::postReportCount($post['id']);
            if((int)$reportCount>2){
                array_push($reportedPosts, new Post($post['id'],$post['username'], $post['location'], $post['image'], $post['description'], $post['timestamp'],
                (empty(Post::fetchLikes($post['id']))) ? array() : Post::fetchLikes($post['id']), (empty(Post::fetchComments($post['id']))) ? array() : Post::fetchComments($post['id']), $post['filter']));
            }
        }
        return $reportedPosts;
        
    }
    public static function deleteStrikes($postId){
        $conn = Db::getConnection();
        $statement = $conn->prepare("DELETE FROM `post_strikes` WHERE post_id = :postId;");
        $statement->bindValue(":postId", $postId);
        $result = $statement->execute();
        return $result;
    }
    public static function deletePost($postId){
        Post::deleteStrikes($postId);
        Post::deletePostImage($postId);
        Post::deleteCommentLikes($postId);
        Post::deletePostComments($postId);
        Post::deletePostLikes($postId);
        $conn = Db::getConnection();
        $statement = $conn->prepare("DELETE FROM `posts` WHERE id = :postId;");
        $statement->bindValue(":postId", $postId);
        $result = $statement->execute();
        return $result;
    }
    private static function deletePostComments($postId){
        $conn = Db::getConnection();
        $statement = $conn->prepare("DELETE FROM `comments` WHERE post_id = :postId;");
        $statement->bindValue(":postId", $postId);
        $result = $statement->execute();
        return $result;
    }
    private static function deleteCommentLikes($postId){
        $conn = Db::getConnection();
        $statement = $conn->prepare("DELETE cl
        from comment_likes cl
        inner JOIN comments c
        on cl.comment_id=c.id
        WHERE c.post_id = :postId");
        $statement->bindValue(":postId", $postId);
        $result = $statement->execute();
        return $result;
    }
    private static function deletePostLikes($postId){
        $conn = Db::getConnection();
        $statement = $conn->prepare("DELETE FROM `post_likes` WHERE post_id = :postId;");
        $statement->bindValue(":postId", $postId);
        $result = $statement->execute();
        return $result;
    }
    private static function deletePostImage($postId){
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM `posts` WHERE id = :postId;");
        $statement->bindValue(":postId", $postId);
        $statement->execute();
        $post = $statement->fetch();
        if(!$post){
            throw new Exception('This user does not exist');
        }
        $imageName = $post["image"];
        if(strpos($_SERVER['REQUEST_URI'], "postDetail")) {
            $imagePath = "./uploads/posts/$imageName";
        }else{
            $imagePath = "../uploads/posts/$imageName";
        }
        // Delete file from folder
        if(!unlink($imagePath)){
            throw new Exception('This image does not exist');
        }        
    }
    private static function loadPosts($statement){
        $posts = $statement->fetchAll();
        if (!$posts) {
            throw new Exception('There are no posts found');
        }
        $selectedPosts = array();
        foreach ($posts as $post) {
            $postReportCount = Post::postReportCount($post['id']);
            if($postReportCount<3){
                array_push($selectedPosts, new Post($post['id'],$post['username'],$post['location'], $post['image'], $post['description'], $post['timestamp'],
                (empty(Post::fetchLikes($post['id']))) ? array() : Post::fetchLikes($post['id']), (empty(Post::fetchComments($post['id']))) ? array() : Post::fetchComments($post['id']), $post['filter']));
            }
            
        }
        return $selectedPosts;
    }
    public function toArray()
    {
        $poster = User::fetchUserByUsername($this->getUser());
        $timesAgo = array();
        foreach ($this->getComments() as $comment) {
            array_push($timesAgo, Comment::timeAgo($comment['id']));
        }
        return array(
            "id" => $this->getId(),
            "description" => $this->getDescription(),
            "image" => $this->getImage(),
            "timestamp" => $this->getTimestamp(),
            "user" => $this->getUser(),
            "likes" => $this->getLikes(),
            "comments" => $this->getComments(),
            "posterImage" => $poster->getAvatar(),
            "sessionUser" => $_SESSION['user'],
            "sessionUserId" => User::fetchUserByUsername($_SESSION['user'])->getId(),
            "timeAgo" => $this->postedTimeAgo($this->getId()),
            "commentsAgo" => $timesAgo,
            "filter" => $this->getFilter()
        );
    }
}