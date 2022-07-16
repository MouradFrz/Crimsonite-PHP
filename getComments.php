<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $postid = $_POST['id'];
    include_once './classes/db.php';
    try {
        $pdo = dbConnection::connect();
        $stmt = $pdo->prepare("SELECT * from comments where postid=?");
        $stmt->execute([$postid]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        print_r(json_encode($comments));
    } catch (PDOException $e) {
        echo $e;
    }
} else {
    header('Location: feed.php');
}
