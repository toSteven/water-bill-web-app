<?php 
    session_start();
    require_once("../db_connection.php");
    date_default_timezone_set('Asia/Manila'); 

    $username = $_SESSION['user'] ?? "";
    
    if(isset($_POST["payment_btn"])) {

        $uniqueID = $_POST["payment_btn"];

        //Get Cubic Consume per month
        $consume = mysqli_query($conn,"SELECT * FROM cubic_consume WHERE unique_id='$uniqueID'");
        if(mysqli_num_rows($consume) > 0){
            while($row = mysqli_fetch_assoc($consume)){
                extract($row);
            }
        } 

        //Get Cubic payment status per month
        $stat = mysqli_query($conn,"SELECT * FROM payment_status WHERE unique_id='$uniqueID'");
        if(mysqli_num_rows($stat) > 0){
            while($row = mysqli_fetch_assoc($stat)){
                extract($row);
            }
        } 

        //Array Consume Per Month
        $listMonth =  array(
            $january, 
            $february,
            $march,
            $april,
            $may,
            $june,
            $july,
            $august,
            $september,
            $october,
            $november,
            $december
        );

        //Array for Payment Status Per Month
        $statMonth =  array(
            $stat_january, 
            $stat_february,
            $stat_march,
            $stat_april,
            $stat_may,
            $stat_june,
            $stat_july,
            $stat_august,
            $stat_september,
            $stat_october,
            $stat_november,
            $stat_december
        );

        //Array For checking if checkbox is SET
        $checkVal =  array(
            'jan',
            'feb',
            'mar',
            'apr',
            'may',
            'jun',
            'jul',
            'aug',
            'sept',
            'oct',
            'nov',
            'dec'
        );

        //Array for sql Colomn
        $column =  array(
            'stat_january',
            'stat_february',
            'stat_march',
            'stat_april',
            'stat_may',
            'stat_june',
            'stat_july',
            'stat_august',
            'stat_september',
            'stat_october',
            'stat_november',
            'stat_december'
        );

        if(empty($_POST['payment'])) {
        }else {
            $unpaidCount = 0;
            foreach ($listMonth as $index => $value) {
                if($statMonth[$index]=='paid') {
                } else {
                    if(in_array($checkVal[$index], $_POST['payment']) && !empty($listMonth[$index])){
                        $sqlPaid = "UPDATE payment_status SET ".$column[$index]."='paid' WHERE unique_id='$uniqueID'";
                        if (mysqli_query($conn, $sqlPaid)) {
                            $_SESSION["payment-message"] = 'true';

                            //get primary id 
                            $primary = mysqli_query($conn,"SELECT * FROM client WHERE unique_id='$uniqueID'");
                            if(mysqli_num_rows($primary) > 0){
                                while($row_prime = mysqli_fetch_assoc($primary)){
                                    extract($row_prime);
                                }
                            }

                            // Insert Logs
                            $datetime = date("Y-m-d H:i:s");
                            $sqlLogs = "INSERT INTO logs (name, event_logs, datetime) VALUES ('$username', 'Accepted payment for ".$name."', '$datetime')";
                            $pdo->exec($sqlLogs);

                            header("location: https://water.absierra.com/client-info.php?btn-client=".$id);
                        }
                    } else if($statMonth[$index]==='unpaid') {
                        $unpaidCount +=1;
                    }
                }
            }
        }

        // For counting how many client have no balance
        if($unpaidCount>0){
            $statUnpaid ="UPDATE payment_status SET status='unpaid' WHERE unique_id='$uniqueID'";
            if(mysqli_query($conn, $statUnpaid)) {
            }
        }else{
            $statUnpaid ="UPDATE payment_status SET status='paid' WHERE unique_id='$uniqueID'";
            if(mysqli_query($conn, $statUnpaid)) {
            }
        }
        
        mysqli_close($conn);
    }
?>