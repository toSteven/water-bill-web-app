<?php 
    session_start();

    require_once("../db_connection.php");
    date_default_timezone_set('Asia/Manila'); 

    $username = $_SESSION['user'] ?? "";

    if(isset($_POST["clear-client"])) {

        $confirmation = $_POST["client-confirm"];
        $auth = $_POST["delClientPass"];
        $encryption = md5($auth);

        if($confirmation=="permanently delete") {
            $check_auth = mysqli_query($conn,"SELECT * FROM account WHERE username='$username' and password='$encryption'");
            $result=mysqli_fetch_array($check_auth);
            if($result){
                $deleteClient =  "TRUNCATE TABLE client;";
                $deleteClient .= "TRUNCATE TABLE cubic_consume;";
                $deleteClient .= "TRUNCATE TABLE payment_status";
                if (mysqli_multi_query($conn, $deleteClient)) {
                    //Insert Logs
                    $datetime = date("Y-m-d H:i:s");
                    $sqlLogs = "INSERT INTO logs (name, event_logs, datetime) VALUES ('$username', 'DELETED ALL CLIENT', '$datetime')";
                    $pdo->exec($sqlLogs);

                    $_SESSION["delAllClient-message"] = "true";
                }else{
                    $_SESSION["delAllClient-message"] = "false";
                }
            }else{
                $_SESSION["delAllClient-message"] = "false";
            }
        }else{
            $_SESSION["delAllClient-message"] = "false";
        }
        mysqli_close($conn);
        header("location: ../setting.php");
    }
?>