<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
 header('Location: login.php');
     }
 if($_SERVER["REQUEST_METHOD"]==="POST"){
    $text = $_POST['comment'];
    $postid = $_POST['postid'];
    if(strlen($text)==0){
        $_SESSION['postid']=$postid;
        $_SESSION['unvalid']=true;
        header("Location: feed.php");
        exit();
    }
    
    $username = $_SESSION['loggedin'];

    try{
        include_once './classes/db.php';

        $pdo = dbConnection::connect();
        $stmt = $pdo->prepare('INSERT INTO comments (text,created_at,postid,username) values (?,?,?,?)');
        $stmt->execute([$text,date('Y-m-d H:i:s', time()),$postid,$username]);
    }catch(PDOException $e){
        echo $e;
    }

    $_SESSION['postid']=$postid;
    header("Location: feed.php");
 }else{
    header('Location: feed.php');
 }
?>