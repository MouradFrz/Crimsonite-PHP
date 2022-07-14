<?php session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
}
include './classes/db.php';

$pdo = dbConnection::connect();
$posts = $pdo->query('SELECT * FROM feedposts');
$posts = $posts->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed - Crimsonite</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="unclick">

    </div>
    <div class="custom-modal">
        <h1>Create a new post</h1>
        <form action="">
            <label for="">Text</label>
            <textarea name="" id=""></textarea>
            <input type="file" name="" class="form-control" id="">
            <input type="submit" class="cbtn mt-2" value="Create">
        </form>
    </div>
    <div class="notification">
        <p></p>
    </div>
    <div>
        <nav>
            <div class="container">
                <div class="nav-wrapper">
                    <div class="nav-brand">
                        <img src="./assets/img/logo.png" alt="">
                        <h1><a href="index.php" style="text-decoration:none;color:white;">Crimsonite</a></h1>
                    </div>
                    <div class="nav-list">
                        <ul>
                            <li>
                                <p class="me-3 text-light"><i class="bi bi-person"></i> <strong><?= $_SESSION['loggedin'] ?></strong></p>
                            </li>
                            <li>
                                <form action="logout.php" method="POST" class="m-0">
                                    <input type="submit" value="Logout" class="button-login" style="margin:0">
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <main>
            <div class="container">
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <div class="posts" style="border:none ;">
                        <button class="custom-button" id="new-post">Create a new post</button>
                    </div>
                    <div class="posts mt-0">

                        <div class="post">
                            <p class="username"><i class="bi bi-person"></i> Username</p>
                            <p class="date text-muted">01/01/2001 34:22:31</p>
                            <p class="text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia, dolorum ab velit quidem temporibus labore reprehenderit voluptas optio quos explicabo, distinctio odit accusantium repudiandae cum?</p>
                            <img class="post-image" src="assets/img/posts/wallpaper.jpg" alt="">
                            <div class="interact">
                                <a class="like" href="#">
                                    <i class="bi bi-heart"></i>
                                    <p>Favorite</p>
                                </a>
                                <a class="view-more" href="#">
                                    <i class="bi bi-list"></i>
                                    <p>Comments</p>
                                </a>
                            </div>
                        </div>
                        <?php foreach ($posts as $index => $post) { ?>
                            <div class="post">
                                <p class="username"><i class="bi bi-person"></i> <?= $post['username'] ?> </p>
                                <p class="date text-muted"><?= $post['created_at'] ?></p>
                                <p class="text"><?= $post['text'] ?></p>
                                <?php if (isset($post['image'])) { ?>
                                    <img class="post-image" src="assets/img/posts/<?= $post['image'] ?>" alt="">
                                <?php } ?>
                                <div class="interact">
                                    <a class="like" href="#">
                                        <i class="bi bi-heart"></i>
                                        <p>Favorite <span class="text-muted"><?= $post['likes'] ?></span></p>

                                    </a>
                                    <a class="view-more" href="#">
                                        <i class="bi bi-list"></i>
                                        <p>Comments</p>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>

                    </div>

                </div>
            </div>
        </main>
    </div>
    <script>
        document.querySelector('.unclick').addEventListener('click', () => {
            document.querySelector('.unclick').style.display = 'none';
            document.querySelector('.custom-modal').style.display = 'none';
        })
        document.querySelector('#new-post').addEventListener('click', () => {
            document.querySelector('.unclick').style.display = 'flex';
            document.querySelector('.custom-modal').style.display = 'block';
        })
    </script>
</body>

</html>