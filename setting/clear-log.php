<?php 
    session_start();

    require_once("../db_connection.php");
    date_default_timezone_set('Asia/Manila'); 

    $username = $_SESSION['user'] ?? "";

    if(isset($_GET["clear-logs"])) {

        $confirmation = $_GET["log-confirm"];

        if($confirmation=="permanently delete") {
            $clearLog = "TRUNCATE TABLE logs;";
            if (mysqli_query($conn, $clearLog)) {

                
                //Insert Logs
                $datetime = date("Y-m-d H:i:s");
                $sqlLogs = "INSERT INTO logs (name, event_logs, datetime) VALUES ('$username', 'Clear Activity Logs', '$datetime')";
                $pdo->exec($sqlLogs);

                $_SESSION["clearLog-message"] = "true";
            }else {
                $_SESSION["clearLog-message"] = "false";
            }
        }
        mysqli_close($conn);
        header("location: ../setting.php");
    }
?>