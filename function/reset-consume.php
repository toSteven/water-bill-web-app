<?php 
    session_start();
    require_once("../db_connection.php");
    date_default_timezone_set('Asia/Manila'); 

    $username = $_SESSION['user'] ?? "";

    if(isset($_GET["confirm-reset"])) {

        $uniqueID = $_GET["confirm-reset"];
        $confirmation = $_GET["confirm-resetInput"];
        //get primary id 
        $primary = mysqli_query($conn,"SELECT * FROM client WHERE unique_id='$uniqueID'");
        if(mysqli_num_rows($primary) > 0){
            while($row_prime = mysqli_fetch_assoc($primary)){
                extract($row_prime);
            }
        }
        if($confirmation=='reset permanently') {
            $sql =  "UPDATE cubic_consume SET january='0', february='0', march='0', april='0', may='0',  june='0', july='0', august='0' , september='0', october='0', november='0', december='0', cubic_sum='0', total='0' WHERE unique_id='$uniqueID'";
            
            // Update Client Database
            if (mysqli_query($conn, $sql)) {
                //reset payment status
                $payStatus = "UPDATE payment_status SET stat_january='', stat_february='', stat_march='', stat_april='', stat_may='',  stat_june='', stat_july='', stat_august='' , stat_september='', stat_october='', stat_november='', stat_december='', status='' WHERE unique_id='$uniqueID'";
                if (mysqli_query($conn, $payStatus)) {
                    $_SESSION["reset-message"] = "true";
                    //Insert Logs
                    $datetime = date("Y-m-d H:i:s");
                    $sqlLogs = "INSERT INTO logs (name, event_logs, datetime) VALUES ('$username', 'Reset ".$name." cubic consume data.', '$datetime')";
                    $pdo->exec($sqlLogs);
                }else{
                    $_SESSION["reset-message"] = "false";
                }
            } else {
                $_SESSION["reset-message"] = "false";
            }
        }else {
            $_SESSION["reset-message"] = "false";
        }
        header("location: https://water.absierra.com/client-info.php?btn-client=".$id);
    }
?>