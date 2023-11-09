<?php 
session_start();

include 'koneksi.php';
//atur variabel
$err        = "";
$username   = "";
$ingataku   = "";

if(isset($_COOKIE['cookie_username'])){
    $cookie_username = $_COOKIE['cookie_username'];
    $cookie_password = $_COOKIE['cookie_password'];

    $sql1 = "select * from login where username = '$cookie_username'";
    $q1   = mysqli_query($koneksi,$sql1);
    $r1   = mysqli_fetch_array($q1);
    if($r1['password'] == $cookie_password){
        $_SESSION['session_username'] = $cookie_username;
        $_SESSION['session_password'] = $cookie_password;
    }
}

if(isset($_SESSION['session_username'])){
    header("location:index.php");
    exit();
}

if(isset($_POST['login'])){
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    

    if($username == '' or $password == ''){
        $err .= "<li>Silakan masukkan username dan juga password.</li>";
    }else{
        $sql1 = "select * from login where username = '$username'";
        $q1   = mysqli_query($koneksi,$sql1);
        $r1   = mysqli_fetch_array($q1);

        if(empty($r1['username'])){
            $err .= "<li>Username <b>$username</b> tidak tersedia.</li>";
        }else if($r1['password'] != md5($password)){
            $err .= "<li>Password yang dimasukkan tidak sesuai.</li>";
        }       
        
        if(empty($err)){
            $_SESSION['session_username'] = $username; //server
            $_SESSION['session_password'] = md5($password);

            if($ingataku == 1){
                $cookie_name = "cookie_username";
                $cookie_value = $username;
                $cookie_time = time() + (60 * 60 * 24 * 30);
                setcookie($cookie_name,$cookie_value,$cookie_time,"/");

                $cookie_name = "cookie_password";
                $cookie_value = md5($password);
                $cookie_time = time() + (60 * 60 * 24 * 30);
                setcookie($cookie_name,$cookie_value,$cookie_time,"/");
            }
            header("location:index.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background: #15c325;
            color: #fff;
            font-family: 'Arial', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 300px;
            text-align: center;
        }

        .panel {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px;
        }

        .btn-login {
            background-color: #fff;
            color: #3498db;
            font-weight: bold;
            border-radius: 8px;
            padding: 10px;
            width: 100%;
        }

        .btn-login:hover {
            background-color: #258cd1;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="panel">
            <div class="panel-heading">Login</div>
            <div class="panel-body">
                <?php if($err) { ?>
                    <div class="alert alert-danger">
                        <?php echo $err; ?>
                    </div>
                <?php } ?>
                <form class="form-horizontal" action="" method="post" role="form">
                    <div class="form-group">
                        <input id="login-username" type="text" class="form-control" name="username" value="<?php echo $username ?>" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input id="login-password" type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input id="login-remember" type="checkbox" name="ingataku" value="1" <?php if($ingataku == '1') echo "checked"?>> Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="login" class="btn btn-login" value="Login" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
