<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
}

include './classes/db.php';
$pdo = dbConnection::connect();



$stmt1 = $pdo->prepare('SELECT * FROM posts ORDER BY created_at DESC;');

$stmt1->execute();
$posts = $stmt1->fetchAll(PDO::FETCH_ASSOC);

$earlier = new DateTime($posts[0]['created_at']);
$later = new DateTime();



$pos_diff = $earlier->diff($later)->format("%r%a"); //3
if ($pos_diff != 0) {
    $curl = curl_init();
    $url = 'https://finnhub.io/api/v1/news?category=general&token=cb42t6iad3i5aivhfgb0';
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    $resp = curl_exec($curl);


    
    $stmt1 = $pdo->prepare('INSERT into posts (`message`,`created_at`) values (?,now());');
    $stmt1->execute([json_decode($resp)[rand(0,count(json_decode($resp))-1)]->summary]);
}

$stmt1 = $pdo->prepare('SELECT * FROM posts ORDER BY created_at DESC;');
$stmt1->execute();
$posts = $stmt1->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->prepare('SELECT * FROM feedback WHERE post=? ORDER BY created_at DESC;');
$stmt->execute([$posts[0]['id']]);
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .wrapper {
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            width: 100%;
        }
    </style>
</head>

<body>
       
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
                <div class="wrapper">
                    <h2 class="mb-3 feed-title">Today's headline</h2>
                    <p style="color:white;max-width:900px" class="mb-5 text-center"> <?= $posts[0]['message']; ?> </p>
                    <div style="width:80% ;">

                        <?php
                        if (count($feedbacks)) {
                            foreach ($feedbacks as $index => $feedback) {
                        ?>
                                <div class="feedback">
                                    <?php if ($_SESSION['loggedin'] == $feedback['sender']) { ?>
                                        <i data-id="<?= $feedback['id'] ?>" class="bi bi-pencil"></i>
                                    <?php } ?>
                                    <i data-id="<?= $feedback['id'] ?>" class="bi bi-check-lg"></i>
                                    <p><strong style="color:#E7E27C ;">BY : </strong><?= $feedback['sender'] ?></p>
                                    <p id="message-<?= $feedback['id'] ?>" class="message"><?= $feedback['message'] ?></p>
                                    <p class="time"><?= $feedback['created_at'] ?></p>
                                </div>
                        <?php }
                        } else {
                            echo '<h4 class="text-center text-light mb-3">There is currently no feedbacks</h4>';
                        } ?>
                        <div>
                            <form action="addFeedback.php" method="POST">
                                <textarea name="feedback" id="" class="feedback-field"></textarea>
                                <input type="submit" value="Send feedback" class="cbtn">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php 
            include 'footer.php';
            ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script>
        let success = false;
        <?php
        if (isset($_SESSION['success']) && $_SESSION['success'] == true) { ?>
            success = true;
        <?php $_SESSION['success'] = false;
        } ?>

        const notification = document.querySelector('.notification');
        const notificationMessage = document.querySelector('.notification>p');
        if (success) {
            notificationMessage.textContent = "Thanks for your feedback!";
            notification.classList.toggle('show');
            setTimeout(() => {
                notification.classList.toggle('show');
            }, 2000);
        }

        const icons = document.querySelectorAll('.bi-pencil');
        const checks = document.querySelectorAll('.bi-check-lg');
        let oldMessage;
        icons.forEach(e => {
            e.addEventListener('click', () => {
                oldMessage = document.querySelector(`#message-${e.dataset.id}`).textContent;
                document.querySelector(`#message-${e.dataset.id}`).setAttribute('contenteditable', 'true');
                document.querySelector(`#message-${e.dataset.id}`).focus();
                document.querySelector(`.bi-check-lg[data-id="${e.dataset.id}"]`).style.display = "block";
            })
        })

        checks.forEach(e => {
            e.addEventListener('click', () => {

                $.ajax({
                    type: "POST",
                    url: '/editfeedback.php',
                    data: {
                        id: e.dataset.id,
                        message: document.querySelector(`#message-${e.dataset.id}`).textContent
                    },
                    success: function() {
                        document.querySelector(`#message-${e.dataset.id}`).setAttribute('contenteditable', 'false');
                        document.querySelector(`.bi-check-lg[data-id="${e.dataset.id}"]`).style.display = "none";
                        if (oldMessage === document.querySelector(`#message-${e.dataset.id}`).textContent) {
                            notificationMessage.textContent = "No changes";
                        } else {
                            notificationMessage.textContent = "Feedback edited successfully";
                        }

                        notification.classList.toggle('show');
                        setTimeout(() => {
                            notification.classList.toggle('show');
                        }, 2000);
                    }
                })




            })
        })
    </script>
</body>

</html>