<?php session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
}
include './classes/db.php';
$username = $_SESSION['loggedin'];
$pdo = dbConnection::connect();
$posts = $pdo->query('SELECT * FROM feedposts ORDER BY created_at desc');
$posts = $posts->fetchAll(PDO::FETCH_ASSOC);

$likedposts = $pdo->query("SELECT feedpostid from likes WHERE username='${username}';");
$likedposts = $likedposts->fetchAll(PDO::FETCH_ASSOC);
$likedpostslist = [];
foreach ($likedposts as $index => $post) {
    $likedpostslist[] = $post['feedpostid'];
}


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $text = $_POST['text'];
    if (!empty($_FILES['myImage']['name'])) {
        $file_tmp = $_FILES['myImage']['tmp_name'];
        $ext = explode('.', $_FILES['myImage']['name']);
        $ext = $ext[count($ext) - 1];
        $file_name = time() . $_SESSION['loggedin'] . '.' . $ext;
        move_uploaded_file($file_tmp, "assets/img/posts/${file_name}");
    }

    $stmt = $pdo->prepare('INSERT INTO feedposts (username,likes,text,image,created_at) values (?,0,?,?,?)');
    $stmt->execute([$_SESSION['loggedin'], $text, $file_name ?? null, date('Y-m-d H:i:s', time())]);
    $pdo = null;
    header('Location: feed.php');
}
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
    <div class="details-panel">
        <div class="post-details">
            <p class="username"><i class="bi bi-person"></i> </p>
            <p class="date text-muted"></p>
            <p class="text"></p>
            <img class="post-image" src="" alt="">
            <div class="comments">
                <div class="comment">
                    <p class="username"><i class="bi bi-person"></i> mourad </p>
                    <p class="date text-muted">09/09/2001 22:00:24</p>
                    <p class="text">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Excepturi accusantium accusamus similique adipisci repellat, praesentium perferendis suscipit rerum! Adipisci, rerum?</p>
                </div>
            </div>

        </div>
    </div>
    <div class="custom-modal">
        <h1>Create a new post</h1>
        <form action="feed.php" method="post" enctype="multipart/form-data">
            <label for="">Text</label>
            <textarea name="text" id=""></textarea>
            <input type="file" accept="image/*" name="myImage" class="form-control" id="">
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
                        <?php foreach ($posts as $index => $post) { ?>
                            <div class="post">
                                <p class="username" data-id=<?= $post['id'] ?>><i class="bi bi-person"></i> <?= $post['username'] ?> </p>
                                <p class="date text-muted" data-id=<?= $post['id'] ?>><?= $post['created_at'] ?></p>
                                <p class="text" data-id=<?= $post['id'] ?>><?= $post['text'] ?></p>
                                <?php if (isset($post['image'])) { ?>
                                    <img data-id=<?= $post['id'] ?> class="post-image" src="assets/img/posts/<?= $post['image'] ?>" alt="">
                                <?php } ?>
                                <div class="interact">
                                    <a class="like" data-id=<?= $post['id'] ?>>
                                        <i data-id=<?= $post['id'] ?> class="bi bi-heart<?php if (in_array($post['id'], $likedpostslist)) {
                                                                                            echo '-fill';
                                                                                        } ?>"></i>
                                        <p>Favorite <span data-id=<?= $post['id'] ?> class="text-muted"><?= $post['likes'] ?></span></p>
                                    </a>
                                    <a class="view-more" data-id=<?= $post['id'] ?>>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script>
        document.querySelector('.unclick').addEventListener('click', () => {
            document.querySelector('.unclick').style.display = 'none';
            document.querySelector('.custom-modal').classList.remove('show');
            document.querySelector('.details-panel').classList.remove('show');
        })
        document.querySelector('#new-post').addEventListener('click', () => {
            document.querySelector('.unclick').style.display = 'flex';
            document.querySelector('.custom-modal').classList.add('show');
        })
    </script>
    <script>
        let likes = document.querySelectorAll('.like');
        likes.forEach(e => {
            e.addEventListener('click', () => {
                let myIcon = document.querySelector(`i[data-id="${e.dataset.id}"]`);
                if (myIcon.classList.contains('bi-heart-fill')) {
                    $.ajax({
                        type: "POST",
                        url: 'unlike.php',
                        data: {
                            id: e.dataset.id,
                        },
                        success: function(res) {
                            myIcon.classList.remove('bi-heart-fill')
                            myIcon.classList.add('bi-heart')
                            document.querySelector(`span[data-id="${e.dataset.id}"]`).textContent = res
                        }
                    })
                } else {
                    $.ajax({
                        type: "POST",
                        url: 'like.php',
                        data: {
                            id: e.dataset.id,
                        },
                        success: function(res) {
                            myIcon.classList.remove('bi-heart')
                            myIcon.classList.add('bi-heart-fill')
                            document.querySelector(`span[data-id="${e.dataset.id}"]`).textContent = res
                        }
                    })
                }

            })
        })
    </script>
    <script>
        const comments = document.querySelectorAll('.view-more');
        const posttext = document.querySelector('.post-details>.text')
        const postusername = document.querySelector('.post-details>.username')
        const postdate = document.querySelector('.post-details>.date')
        const postimage = document.querySelector('.post-details>.post-image')


        comments.forEach(e => {
            e.addEventListener('click', () => {
                //loading the right post following the id data attribute
                let ID = e.dataset.id
                posttext.textContent = document.querySelector(`p.text[data-id="${e.dataset.id}"]`).textContent
                postusername.textContent = document.querySelector(`p.username[data-id="${e.dataset.id}"]`).textContent
                postdate.textContent = document.querySelector(`p.date[data-id="${e.dataset.id}"]`).textContent
                if(document.querySelector(`img.post-image[data-id="${e.dataset.id}"]`)){
                postimage.setAttribute('src', document.querySelector(`img.post-image[data-id="${e.dataset.id}"]`).src)
                }
                document.querySelector('.unclick').style.display = 'flex';
                document.querySelector('.details-panel').classList.add('show');

                //clearing the old loaded comments
                document.querySelector('.comments').textContent = '';

                //loading comments for current post
                $.ajax({
                    type: "POST",
                    url: 'getComments.php',
                    data: {
                        id: e.dataset.id,
                    },
                    success: function(res) {
                        let data = JSON.parse(res);
                        console.log(data)
                        data.forEach(obj => {
                            let mydiv = document.createElement('div')
                            mydiv.classList.add('comment')
                            mydiv.innerHTML = `<p class="username"><i class="bi bi-person"></i>${obj.username} </p>
                                                <p class="date text-muted">${obj.created_at}</p>
                                                <p class="text">${obj.text}</p>`;
                            document.querySelector('.comments').appendChild(mydiv);
                        })
                    }
                })

            })
        })
    </script>
</body>

</html>