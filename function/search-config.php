<?php
    require_once("../db_connection.php");

    // Attempt search query execution
    try{
        if(isset($_REQUEST["term"])){
            // create prepared statement
            $sql = "SELECT * FROM client WHERE name LIKE :term";
            $stmt = $pdo->prepare($sql);
            $term = $_REQUEST["term"] . '%';
            // bind parameters to statement
            $stmt->bindParam(":term", $term);
            // execute the prepared statement
            $stmt->execute();
            if($stmt->rowCount() > 0){
                while($row = $stmt->fetch()){
                    if(empty($row["address"])){
                        echo "<a href='https://water.absierra.com/client-info.php?btn-client=".$row["id"]."'><p>" . $row["name"] . "</p></a>";
                    }else{
                        echo "<a href='https://water.absierra.com/client-info.php?btn-client=".$row["id"]."'><p>" . $row["name"] ." | ". $row["address"] . "</p></a>";
                    }
                }
            } else{
                echo "<p>No matches found</p>";
            }
        }  
    } catch(PDOException $e){
        die("ERROR: Could not able to execute $sql. " . $e->getMessage());
    }

    // Close statement
    unset($stmt);
    // Close connection
    unset($pdo);
?>