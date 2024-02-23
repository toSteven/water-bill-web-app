<?php 
    session_start();

    require_once("../db_connection.php");
    date_default_timezone_set('Asia/Manila'); 

    $username = $_SESSION['user'] ?? "";

    if(isset($_GET["clear-consume"])) {

        $confirmation = $_GET["consume-confirm"];

        if($confirmation=="permanently delete") {
            $clearConsume =  "UPDATE cubic_consume SET january='0', february='0', march='0', april='0', may='0',  june='0', july='0', august='0' , september='0', october='0', november='0', december='0', cubic_sum='0', total='0'";
            if (mysqli_query($conn, $clearConsume)) {
                $clearPayStat =  "UPDATE payment_status SET stat_january='', stat_february='', stat_march='', stat_april='', stat_may='',  stat_june='', stat_july='', stat_august='' , stat_september='', stat_october='', stat_november='', stat_december='', status=''";
                if (mysqli_query($conn, $clearPayStat)) {
                    //Insert Logs
                    $datetime = date("Y-m-d H:i:s");
                    $sqlLogs = "INSERT INTO logs (name, event_logs, datetime) VALUES ('$username', 'Delete All Client Cubic Consume Data', '$datetime')";
                    $pdo->exec($sqlLogs);

                    $_SESSION["clearConsume-message"] = "true";
                }
            }else {
                $_SESSION["clearConsume-message"] = "false";
            }
        }
        mysqli_close($conn);
        header("location: ../setting.php");
    }
?>