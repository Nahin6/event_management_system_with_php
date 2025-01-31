<?php
$host = "localhost"; 
$username = "root"; 
$password = "";     
$database = "event_management"; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
   
    die("Database connection failed: " . $e->getMessage());
}
?>
