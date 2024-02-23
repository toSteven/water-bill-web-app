<?php 
    session_start();

    require_once("../db_connection.php");
    date_default_timezone_set('Asia/Manila'); 

    $username = $_SESSION['user'] ?? "";

    if(isset($_GET["update-client"])) {

        $id = $_GET["update-client"];
        $name = $_GET["name"];
        $address = $_GET["address"];
        $consume = $_GET["consume"];
        $contact = $_GET["contact"];

        $sql = "UPDATE client SET name='$name', address='$address', contact='$contact' WHERE id='$id'";
        // Update Client To Database
        if (mysqli_query($conn, $sql)) {

            //Insert Logs
            $datetime = date("Y-m-d H:i:s");
            $sqlLogs = "INSERT INTO logs (name, event_logs, datetime) VALUES ('$username', 'Edited ".$name." information.', '$datetime')";
            $pdo->exec($sqlLogs);

            $_SESSION["edit-message"] = "true";
            header("location: https://water.absierra.com/client-info.php?btn-client=".$id);
        } else {
            $_SESSION["edit-message"] = "false";
        }

        mysqli_close($conn);
    }
?>