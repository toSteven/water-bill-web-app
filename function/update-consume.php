<?php 
    session_start();
    require_once("../db_connection.php");
    date_default_timezone_set('Asia/Manila'); 

    $username = $_SESSION['user'] ?? "";
    $getPrimeID = $_SESSION['passID'];

    echo $id;
    

    if(isset($_POST["update-consume"])) {
        $uniqueID = $_POST["update-consume"];
        $jan = $_POST["jan"];
        $feb = $_POST["feb"];
        $mar = $_POST["mar"];
        $apr = $_POST["apr"];
        $may = $_POST["may"];
        $jun = $_POST["jun"];
        $jul = $_POST["jul"];
        $aug = $_POST["aug"];
        $sept = $_POST["sept"];
        $oct = $_POST["oct"];
        $nov =  $_POST["nov"];
        $dec = $_POST["dec"];

        //Get Cubic Price      
        $cubic_price = mysqli_query($conn,"SELECT * FROM cu_price");
        if(mysqli_num_rows($cubic_price) > 0){
            while($row = mysqli_fetch_assoc($cubic_price)){
                extract($row);
            }
        }      
        
        //Get Payment Status per month
        $pay_status = mysqli_query($conn,"SELECT * FROM payment_status WHERE unique_id='$uniqueID'");
        if(mysqli_num_rows($pay_status) > 0){
            while($row_status = mysqli_fetch_assoc($pay_status)){
                extract($row_status);
            }
        }

        $statMonth = array(
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

        $getConsume = array(
            $jan,
            $feb,
            $mar,
            $apr,
            $may,
            $jun,
            $jul,
            $aug,
            $sept,
            $oct,
            $nov,
            $dec
        );

        $column = array(
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
        
        // UPDATE PAYMENT STATUS TO UNPAID
        $unpaidCount = 0;
        foreach($statMonth as $index => $value) {
            if(empty($statMonth[$index]) && !empty($getConsume[$index])) {
                $sqlUnpaid ="UPDATE payment_status SET ".$column[$index]."='unpaid' WHERE unique_id='$uniqueID'";
                if(mysqli_query($conn, $sqlUnpaid)) {
                    $unpaidCount +=1;
                }
            }
        }

        // For counting how many client have no balance
        if($unpaidCount > 0 ){
            $statUnpaid ="UPDATE payment_status SET status='unpaid' WHERE unique_id='$uniqueID'";
            if(mysqli_query($conn, $statUnpaid)) {
            }
        }
        
        //Sum all cubic data
        $cubicSum = ((int)$jan + (int)$feb + (int)$mar + (int)$apr + (int)$may + (int)$jun + (int)$jul + (int)$aug + (int)$sept + (int)$oct + (int)$nov + (int)$dec); 
        $consumeTotal = $cubicSum * $cu_price; // total sum multiply to current cubic price

        // Update cubic consume per month
        try {
            if(!empty($jan)) {
                $updateConsume = "UPDATE cubic_consume SET january='$jan' WHERE unique_id='$uniqueID'";
                $stmt = $conn->prepare($updateConsume);
                $stmt->execute();
            }

            if(!empty($feb)) {
                $updateConsume = "UPDATE cubic_consume SET february='$feb' WHERE unique_id='$uniqueID'";
                $stmt = $conn->prepare($updateConsume);
                $stmt->execute();
            }

            if(!empty($mar)) {
                $updateConsume = "UPDATE cubic_consume SET march='$mar' WHERE unique_id='$uniqueID'";
                $stmt = $conn->prepare($updateConsume);
                $stmt->execute();
            }

            if(!empty($apr)) {
                $updateConsume = "UPDATE cubic_consume SET april='$apr' WHERE unique_id='$uniqueID'";
                $stmt = $conn->prepare($updateConsume);
                $stmt->execute();
            }

            if(!empty($may)) {
                $updateConsume = "UPDATE cubic_consume SET may='$may' WHERE unique_id='$uniqueID'";
                $stmt = $conn->prepare($updateConsume);
                $stmt->execute();
            }

            if(!empty($jun)) {
                $updateConsume = "UPDATE cubic_consume SET june='$jun' WHERE unique_id='$uniqueID'";
                $stmt = $conn->prepare($updateConsume);
                $stmt->execute();
            }

            if(!empty($jul)) {
                $updateConsume = "UPDATE cubic_consume SET july='$jul' WHERE unique_id='$uniqueID'";
                $stmt = $conn->prepare($updateConsume);
                $stmt->execute();
            }

            if(!empty($aug)) {
                $updateConsume = "UPDATE cubic_consume SET august='$aug' WHERE unique_id='$uniqueID'";
                $stmt = $conn->prepare($updateConsume);
                $stmt->execute();
            }

            if(!empty($sept)) {
                $updateConsume = "UPDATE cubic_consume SET september='$sept' WHERE unique_id='$uniqueID'";
                $stmt = $conn->prepare($updateConsume);
                $stmt->execute();
            }

            if(!empty($oct)) {
                $updateConsume = "UPDATE cubic_consume SET october='$oct' WHERE unique_id='$uniqueID'";
                $stmt = $conn->prepare($updateConsume);
                $stmt->execute();
            }

            if(!empty($nov)) {
                $updateConsume = "UPDATE cubic_consume SET november='$nov' WHERE unique_id='$uniqueID'";
                $stmt = $conn->prepare($updateConsume);
                $stmt->execute();
            }

            if(!empty($dec)) {
                $updateConsume = "UPDATE cubic_consume SET december='$dec' WHERE unique_id='$uniqueID'";
                $stmt = $conn->prepare($updateConsume);
                $stmt->execute();
            }

            //Update cubic sum and total to pay
            $sumUpdate =  "UPDATE cubic_consume SET cubic_sum='$cubicSum', total='$consumeTotal' WHERE unique_id='$uniqueID'";
            $stmt = $conn->prepare($sumUpdate);
            $stmt->execute();
            
            // Insert Logs
            $datetime = date("Y-m-d H:i:s");
            $sqlLogs = "INSERT INTO logs (name, event_logs, datetime) VALUES ('$username', 'Update ".$name." cubic consume data.', '$datetime')";
            $pdo->exec($sqlLogs);

            header("location: https://water.absierra.com/client-info.php?btn-client=$getPrimeID");
        } catch(PDOException $e) {
            $_SESSION["consume-message"] = "false";
            header("location: https://water.absierra.com/client-info.php?btn-client=$getPrimeID");
        }
    }
?>