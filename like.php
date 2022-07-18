<?php
session_start();
   if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
        }
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $postid=$_POST['id'];
        $userid=$_SESSION['loggedin'];

        include_once './classes/db.php';
        try{
            $pdo = dbConnection::connect();
            $stmt = $pdo->prepare("INSERT INTO likes (username,feedpostid) values (?,?)");
            $stmt->execute([$userid,$postid]);
            $stmt = $pdo->prepare("SELECT likes from feedposts where id=?");
            $stmt->execute([$postid]);
            $likes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $likes = $likes[0]['likes'];
            $stmt = $pdo->prepare("UPDATE feedposts SET likes=? WHERE id=?");
            $stmt->execute([$likes+1,$postid]);
            $stmt = $pdo->prepare("SELECT count('feedpostid') from likes where feedpostid=?");
            $stmt->execute([$postid]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            print_r($result[0]["count('feedpostid')"]);
        }catch(PDOException $e){
            $stmt = $pdo->prepare("SELECT count('feedpostid') from likes where feedpostid=?");
            $stmt->execute([$postid]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            print_r($result[0]["count('feedpostid')"]);
        }
 

    }else{
        header('Location: feed.php');
    }
?>