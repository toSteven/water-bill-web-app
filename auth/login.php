<?php
    session_start();
    require_once("../db_connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="../css/login.css">
    <script src="../js/tata.js"></script>
</head>
<body>
    <div class="wrapper fadeInDown">
        <div id="formContent">
            <!-- Tabs Titles -->
            <h2 class="active"> Sign In </h2>

            <!-- Icon -->
            <!-- <div class="fadeIn first">
                <img src="../images/avatar.jpg" id="icon" alt="User Icon" />
            </div> -->

            <!-- Login Form -->
            <form method="POST">
                <select name="username" class="fadeIn second" id="login">
                    <option value="President">President</option>
                    <option value="Secretary">Secretary</option>
                    <option value="Auditor">Auditor</option>
                    <option value="Treasurer">Treasurer</option>
                </select>
                <input name="password" type="password" id="password" class="fadeIn second" name="login" placeholder="password">
                <input type="submit" name="btn-login" class="fadeIn second" value="Log In">
            </form>

            <!-- Remind Passowrd -->
            <div id="formFooter">
            <a class="underlineHover" href="#">Forgot Password?</a>
            </div>

            <?php 
                if(isset($_POST['btn-login'])){
                    $username = $_POST['username'];
                    $password = $_POST['password'];
            
                    $encryption = md5($password);
            
            
                    $auth = mysqli_query($conn,"select * from account where username='$username'and password='$encryption'");
                    $result=mysqli_fetch_array($auth);
                    if($result){
                        $_SESSION['login'] = "login";
                        $_SESSION['user'] = "$username";
                        header('Location: ../index.php');
                        die();
                    }else{
                        ?>
                        <script type="text/javascript">tata.error('ERROR', 'Access Denied!', {position: 'tr', duration: 5000})</script>
                        <?php
                    }
                }
            ?>

        </div>
    </div>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</body>
</html>