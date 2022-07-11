<?php

session_start();

if($_SERVER['REQUEST_METHOD']==="POST"){
    $message=$_POST['feedback'];
    include './classes/db.php';
    $pdo = dbConnection::connect();

    $stmt1 = $pdo->prepare('SELECT * FROM posts ORDER BY created_at DESC;');
    $stmt1->execute();
    $posts = $stmt1->fetchAll(PDO::FETCH_ASSOC);


    $stmt = $pdo->prepare('insert into feedback (sender,message,created_at,post) values(?,?,?,?)');
    $stmt->execute([$_SESSION['loggedin'],$message,date('Y-m-d H:i:s', time()),$posts[0]['id']]);



    
    $_SESSION['success']=true;
    header("Location: dashboard.php");
}else{
    header('Location: dashboard.php');
}

?>