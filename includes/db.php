<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    $ConnectingDb = new PDO("mysql:host=$servername;dbname=cms", $username, $password);
    // set the PDO error mode to exception
    $ConnectingDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // echo "<h1>Connected successfully</h1>";
    }
catch(PDOException $e)
    {
    echo "<h1>Connection failed: </h1>" . $e->getMessage();
    }
?>