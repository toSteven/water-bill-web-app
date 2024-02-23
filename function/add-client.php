<?php 
    session_start();
    require_once("../db_connection.php");
    date_default_timezone_set('Asia/Manila'); 

    //SESSION
    $username = $_SESSION['user'] ?? "";
    
    if(isset($_POST["submit-client"])) {
        $name = $_POST["name"];
        $address= $_POST["address"];
        $status = 'no balance';
        $contact = $_POST["contact"];
 
        if(empty($name)) {
            $_SESSION["add-message"] = "false";
            header("location: ../index.php");
        }else{
            // Insert Client To Database
            try {
                // get all register unique id for checking
                $idChecker = mysqli_query($conn,"SELECT * FROM cubic_consume");
                if(mysqli_num_rows($idChecker) > 0){
                    while($row = mysqli_fetch_assoc($idChecker)){
                        extract($row);
                    }
                }

                $uniqueID = uniqid(); //code to genrate new unique ID
                if($unique_id==$uniqueID) 
                {
                    $uniqueID = uniqid(); //generate new unique ID if ID is already in the database

                    //Insert client to client table
                    $sqlClient = "INSERT INTO client (unique_id, name, address, status, contact) VALUES ('$uniqueID','$name', '$address', '$status', $contact)"; //Insert new client
                    $pdo->exec($sqlClient); 

                    //create client a table for cubic consume
                    $sqlCuConsume = "INSERT INTO cubic_consume (unique_id) VALUES ('$uniqueID')"; // create new row for new client
                    $pdo->exec($sqlCuConsume);

                    //create client a table for payment status per month
                    $sqlCuConsume = "INSERT INTO cubic_consume (unique_id) VALUES ('$uniqueID')"; // create new row for new client
                    $pdo->exec($sqlCuConsume);

                    //Insert Logs
                    $datetime = date("Y-m-d H:i:s");
                    $sqlLogs = "INSERT INTO logs (name, event_logs, datetime) VALUES ('Admin', 'Admin insert ".$name." as new client.', '$datetime')";
                    $pdo->exec($sqlLogs);
                } else {
                    $sqlClient = "INSERT INTO client (unique_id, name, address, status, contact) VALUES ('$uniqueID','$name', '$address', '$status', '$contact')";
                    $pdo->exec($sqlClient);

                    $sqlCuConsume = "INSERT INTO cubic_consume (unique_id) VALUES ('$uniqueID')";
                    $pdo->exec($sqlCuConsume);

                    $sqlStatus = "INSERT INTO payment_status (unique_id) VALUES ('$uniqueID')";
                    $pdo->exec($sqlStatus);

                    //Insert Logs
                    $datetime = date("Y-m-d H:i:s");
                    $sqlLogs = "INSERT INTO logs (name, event_logs, datetime) VALUES ('$username', 'Added ".$name." as a new client.', '$datetime')";
                    $pdo->exec($sqlLogs);
                }
                
                $_SESSION["add-message"] = "true";
                header("location: ../index.php");
            } catch(PDOException $e) {
                echo $sql . "<br>" . $e->getMessage();
            }
            $pdo = null;
        }
    }
?>