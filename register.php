<?php
session_start();
if(isset($_SESSION['loggedin'])){
    header('Location: dashboard.php');
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['passwordConfirm'];

    $error;
    $succes;

    include './classes/user.php';

    $user = new User($username, $email, $password, $passwordConfirm);

    if(!$user->notEmpty()){
        $error = "All fields should be filled";
    }
    elseif(!$user->validUsername()){
        $error = "Username Should contain only letters and numbers";
    }
    elseif(!$user->validEmail()){
        $error = "Email format invalid.";
    }
    elseif(!$user->pwMatch()){
        $error = "The passwords don't match";
    }
    elseif(!$user->checkUser()){
        $error = "Username or email already taken";
    }else{
        include './classes/db.php';
        $pdo = dbConnection::connect();
        $stmt = $pdo->prepare('insert into users values(?,?,?)');
        $stmt->execute([$user->getusername(),$user->getemail(),password_hash($user->getpassword(),PASSWORD_BCRYPT)]);
        $pdo = null;
        $succes="Registered successfully!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
    <style>
        .wrapper{
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            width:100%;
        }
    </style>
<body>
    <div class="cursor"></div>
    <div class="wrapper">
        <div class="container">
            <div class="form">
                <h1>Register</h1>

                <form action="register.php" method="POST">
                <?php if(!empty($succes)){ ?>
                <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                    <?php echo $succes; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php } ?>
                <?php if(!empty($error)){ ?>
                <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php } ?>
                    <label for="">Username</label>
                    <input type="text" name="username" value="<?php ($_SERVER['REQUEST_METHOD']=="POST"&& empty($succes) && $_POST['username']) ?print_r($_POST['username']):''?>" class="custom-input">
                    <label for="">Email</label>
                    <input type="text" name="email" value="<?php ($_SERVER['REQUEST_METHOD']=="POST" && empty($succes) && $_POST['email']) ?print_r($_POST['email']):''?>" class="custom-input">
                    <label for="">Password</label>
                    <input type="password" name="password" id="" class="custom-input">
                    <label for="">Confirm Password</label>
                    <input type="password" name="passwordConfirm" id="" class="custom-input">
                    <input type="submit" value="Register" class="cbtn mt-2">
                </form>
                <p class="m-3">Already have an account? <a href="login.php" style="color:#E7E27C">Login!</a></p>
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