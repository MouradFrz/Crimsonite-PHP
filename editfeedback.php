<?php 
session_start();
if($_SERVER['REQUEST_METHOD']==="POST"){
    $id = $_POST['id'];
    $message = $_POST['message'];

    include './classes/db.php';

    $pdo = dbConnection::connect();
    $stmt = $pdo->prepare('select * from feedback where id=? ');
    $stmt->execute([$id]);
    $feedback = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($_SESSION['loggedin']==$feedback[0]['sender']){
        $stmt = $pdo->prepare('UPDATE feedback SET message=? where id=? ');
        $stmt->execute([$message,$id]);
        $pdo=null;
    }else{
        echo 'not allowed';
    }
}
