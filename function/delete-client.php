<?php 
    session_start();
    require_once("../db_connection.php");
    date_default_timezone_set('Asia/Manila'); 

    //SESSION
    $username = $_SESSION['user'] ?? "";
    
    if(isset($_GET["delete-client"])) {

        $uniqueID = $_GET["delete-client"];
        $confirmation = $_GET["confirm-delete"];

        // get primary id for returning to client profile
        $primary = mysqli_query($conn,"SELECT * FROM client WHERE unique_id='$uniqueID'");
        if(mysqli_num_rows($primary) > 0){
            while($row_prime = mysqli_fetch_assoc($primary)){
                extract($row_prime);
            }
        }

        // Delete client
        try {
            if($confirmation=='permanently delete') {
                $sqlClient = "DELETE FROM client WHERE unique_id='$uniqueID'"; //Delete Client table
                $pdo->exec($sqlClient);
    
                $sqlConsume = "DELETE FROM cubic_consume WHERE unique_id='$uniqueID'"; //Delete Client in cubic consume table
                $pdo->exec($sqlConsume);

                $sqlStatus = "DELETE FROM payment_status WHERE unique_id='$uniqueID'"; //Delete Client in payment status table
                $pdo->exec($sqlStatus);

                //Insert Logs
                $datetime = date("Y-m-d H:i:s");
                $sqlLogs = "INSERT INTO logs (name, event_logs, datetime) VALUES ('$username', 'Admin delete ".$name." from client list.', '$datetime')";
                $pdo->exec($sqlLogs);
    
                $_SESSION["del-message"] = "true";
                header("location: ../clients.php");
            }else {
                $_SESSION["del-message"] = "false";
                header("location: https://water.absierra.com/client-info.php?btn-client=".$id);
            }
        } catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }

        $pdo = null;
    }
?>