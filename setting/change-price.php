<?php 
    session_start();

    require_once("../db_connection.php");
    date_default_timezone_set('Asia/Manila'); 

    $username = $_SESSION['user'] ?? "";

    if(isset($_POST["update-price"])) {
        $newPrice = $_POST["price"];
        $confirm = $_POST["price-confirm"];

        if($confirm=="confirm" && !empty($newPrice)) {
            $updatePrice = "UPDATE cu_price SET cu_price='$newPrice' WHERE id='1'";
            // Update Client To Database
            if (mysqli_query($conn, $updatePrice)) {

                //Insert Logs
                $datetime = date("Y-m-d H:i:s");
                $sqlLogs = "INSERT INTO logs (name, event_logs, datetime) VALUES ('$username', 'Edited Cubic Price to ₱ ".$newPrice."', '$datetime')";
                $pdo->exec($sqlLogs);

                $_SESSION["price-message"] = "true";
            }
        }else{
            $_SESSION["price-message"] = "false";
        }
    }else{
        $_SESSION["price-message"] = "false";
    }
    mysqli_close($conn);
    header("location: ../setting.php");
?>