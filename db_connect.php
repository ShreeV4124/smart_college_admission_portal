<?php
function connectDB() {
    try{
        $pdo = new PDO("mysql:host=localhost;dbname=login_db", "root","");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected!";
        return $pdo;
    }catch(PDOException $e){
        echo "Connectoin failed: " . $e->getMessage();
    }
    
}
?>
