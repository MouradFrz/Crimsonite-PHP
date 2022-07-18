<?php
session_start();
if(isset($_SESSION['loggedin'])){
    header('Location: dashboard.php');
}
if($_SERVER['REQUEST_METHOD']==="POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $_POST['password']=$password;
    
    $error;

    include './classes/db.php';
    $pdo = dbConnection::connect();
    $stmt = $pdo->prepare('SELECT username,password FROM users WHERE username=?');
    $stmt->execute([$username]);
    $users=$stmt->fetchAll(PDO::FETCH_ASSOC);

    if($stmt->rowCount()==0){
        $error = "Your account doesn't exist";
    }elseif(!password_verify($password,$users[0]['password'])){
        $error="Wrong password";
    }else{
        session_start();
        $_SESSION['loggedin']=strtolower($username);
        header('Location: dashboard.php');
    }
    $pdo = null;
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
    <style>

        .wrapper{
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            width:100%;
        }
    </style>
</head>
<body>
    <div class="cursor"></div>
    <div class="wrapper">
        <div class="container">
            <div class="form">
                <h1>Login</h1>
                
                <form action="login.php" method="POST">
                <?php if(!empty($error)){ ?>
                <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php } ?>
                    <label for="">Username</label>
                    <input type="text" name="username" class="custom-input">
                    <label for="">Password</label>
                    <input type="password" name="password" id="" class="custom-input">
                    <input type="submit" value="Login" class="cbtn mt-2">
                </form>
                <p class="m-3">Don't have an account? <a href="register.php" style="color:#E7E27C">Register now!</a></p>
            </div>
        </div>
    </div>

    <script>
        const cursor = document.querySelector('.cursor')

        document.addEventListener('mousemove', e => {
            cursor.setAttribute("style", `top:${e.pageY}px;left:${e.pageX}px`);
        })
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>