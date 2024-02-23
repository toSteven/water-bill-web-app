<?php 
    session_start();

    require_once("../db_connection.php");
    date_default_timezone_set('Asia/Manila'); 

    $username = $_SESSION['user'] ?? "";

    if(isset($_POST["update-pass"])) {
        $old = $_POST["old-pass"];
        $new =  $_POST["new-pass"];
        $confirm = $_POST["conf-pass"];

        $encryption = md5($old);
        $check_auth = mysqli_query($conn,"SELECT * FROM account WHERE username='$username' and password='$encryption'");
        $result_auth=mysqli_fetch_array($check_auth);
        if($result_auth){
            if($new==$confirm) {
                $new_encrypt = md5($new);
                $update_auth = "UPDATE account SET password='$new_encrypt' WHERE username='$username'";
                // Update Client To Database
                if (mysqli_query($conn, $update_auth)) {
                    $_SESSION["auth-message"] = "true";
                }
            } else {
                $_SESSION["auth-message"] = "wrong";
            }
        }else{
            $_SESSION["auth-message"] = "wrong";
        }
        
    }else{
        $_SESSION["auth-message"] = "false";
    }
    mysqli_close($conn);
    header("location: ../setting.php");
?>