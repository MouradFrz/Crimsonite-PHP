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
            $stmt = $pdo->prepare("DELETE FROM likes WHERE username=? AND feedpostid=?");
            $stmt->execute([$userid,$postid]);
        }catch(PDOException){
            echo 'ongoing';
        }
        try{
            $stmt = $pdo->prepare("SELECT likes from feedposts where id=?");
            $stmt->execute([$postid]);
            $likes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $likes = $likes[0]['likes'];
            $stmt = $pdo->prepare("UPDATE feedposts SET likes=? WHERE id=?");
            $stmt->execute([$likes-1,$postid]);
        }catch(PDOException){
            echo 'on going';
        }
        try{
            $stmt = $pdo->prepare("SELECT count('feedpostid') from likes where feedpostid=?");
            $stmt->execute([$postid]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            print_r($result[0]["count('feedpostid')"]);
        }catch(PDOException){
            echo 'on going';
        }
       

    }else{
        header('Location: feed.php');
    }
?>