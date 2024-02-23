<?php
    $conn = mysqli_connect('localhost', 'root', '','db-water') or die("Connection failed: " . mysqli_connect_error());
	
	try{
        $pdo = new PDO("mysql:host=localhost;dbname=db-water", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
    catch(PDOException $e){
        die("ERROR: Could not connect. " . $e->getMessage());
    }
    
?> 