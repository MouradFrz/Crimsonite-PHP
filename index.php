<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crimsonite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./assets/css/home.css">

</head>

<body>
    <div class="dropdown">
        <ul class="container">
            <li><a href="">Home</a></li>
            <li><a href="">Feed</a></li>
            <li><a href="dashboard.php">Today's headline</a></li>
            <?php if (!isset($_SESSION['loggedin'])) { ?>

                <li class="d-flex justify-content-center align-items-center"><a href="login.php" class="button-login m-1">Login</a> <a href="register.php" class="button-login m-1">Register</a></li>
            <?php } ?>
            <?php if (isset($_SESSION['loggedin'])) { ?>
                <li class="d-flex justify-content-center align-items-center">
                    <p class="me-3 text-light text-center" style="margin: 0;"><i class="bi bi-person"></i> <strong><?= $_SESSION['loggedin'] ?></strong></p>
                    <form action="logout.php" method="POST" class="m-0">
                        <input type="submit" value="Logout" class="button-login" style="margin:0">
                    </form>
                </li>
                <li>
                    
                </li>
            <?php } ?>
        </ul>
    </div>
    <div>
        <nav>
            <div class="container">
                <div class="nav-wrapper">
                    <div class="nav-brand">
                        <img src="./assets/img/logo.png" alt="">
                        <h1>Crimsonite</h1>
                    </div>
                    <div class="nav-list links">
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li><a href="feed.php">Feed</a></li>
                            <li><a href="dashboard.php">Today's headline</a></li> 
                        </ul>
                    </div>
                    <button id="toggle"><i class="bi bi-list"></i></button>
                    <div class="nav-list log-btns">

                        <ul>
                            <?php if (isset($_SESSION['loggedin'])) { ?>
                                <li>
                                    <p class="me-3 text-light" style="margin: 0;"><i class="bi bi-person"></i> <strong><?= $_SESSION['loggedin'] ?></strong></p>
                                </li>
                                <li>
                                    <form action="logout.php" method="POST" class="m-0">
                                        <input type="submit" value="Logout" class="button-login" style="margin:0">
                                    </form>
                                </li>

                            <?php } ?>
                            <?php if (!isset($_SESSION['loggedin'])) { ?>
                                <li><a href="login.php" class="button-login">Login</a></li>
                                <!-- <li><a href="register.php" class="button-login">Register</a></li> -->
                            <?php } ?>
                        </ul>
                    </div>

                </div>
            </div>
        </nav>
        <div class="gradios">
            <main>
                <div class="container">
                    <div class="hero">
                        <img class="js-animate" src="./assets/img/logo.png" alt="">
                        <h1 class="js-animate">Crimsonite</h1>
                        <span class="js-animate">Crypto as easy as it can be</span>
                        <p class="js-animate">We are a platform where you will learn everything about cryptocurrency
                            You will go from a noobie to a master in 30 days!</p>
                            <a href="register.php" class="custom-button">Get started!</a>
                    </div>
                </div>
            </main>
            <section>
                <div class="container">
                    <div class="how-to">
                        <h2 class="js-fade-in">How to</h2>
                        <div class="ccards">
                            <div class="ccard js-fade-in">
                                <h3>First step</h3>
                                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Obcaecati consequatur
                                    nesciunt, ipsum quos sint fugit quisquam et .</p>
                                <div class="line"></div>
                                <span class="circle"></span>
                                <span class="circle"></span>
                            </div>
                            <div class="ccard js-fade-in">
                                <h3>Second step</h3>
                                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Obcaecati consequatur
                                    nesciunt, ipsum quos sint fugit quisquam et .</p>
                                <div class="line"></div>
                                <span class="circle"></span>
                                <span class="circle"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="container">
                    <div class="newsletter">
                        <div class="news-card js-fade-in">
                            <h2>Newsletter</h2>
                            <p>Enter your email to subscribe to our newsletter</p>
                            <input type="text" placeholder="Email">
                            <button>Subscribe</button>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="container">
                    <div class="services">
                        <h2 class="js-fade-in">Services</h2>
                        <div class="offers">
                            <div class="offer js-fade-in">
                                <img src="./assets/img/base-icon.svg" alt="">
                                <h3>Base</h3>
                                <ul>
                                    <li>Lorem, ipsum dolor.</li>
                                    <li>Lorem, ipsum dolor.</li>
                                    <li>Lorem, ipsum dolor.</li>
                                </ul>
                                <button>REGISTER</button>
                            </div>
                            <div class="offer js-fade-in">
                                <img src="./assets/img/vip-icon.svg" alt="">
                                <h3 style="letter-spacing:4px;">V.I.P</h3>
                                <small>Best Seller</small>
                                <ul>
                                    <li>Lorem, ipsum dolor.</li>
                                    <li>Lorem, ipsum dolor.</li>
                                    <li>Lorem, ipsum dolor.</li>
                                    <li>Lorem, ipsum dolor.</li>
                                    <li>Lorem, ipsum dolor.</li>
                                    <li>Lorem, ipsum dolor.</li>
                                </ul>
                                <button>REGISTER</button>
                            </div>
                            <div class="offer js-fade-in">
                                <img src="./assets/img/advanced-icon.svg" alt="">
                                <h3>Advanced</h3>
                                <ul>
                                    <li>Lorem, ipsum dolor.</li>
                                    <li>Lorem, ipsum dolor.</li>
                                    <li>Lorem, ipsum dolor.</li>
                                </ul>
                                <button>REGISTER</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php
            include 'footer.php';
            ?>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/ScrollTrigger.min.js"></script>
    <script src="./assets/js/main.js"></script>
</body>

</html>