<!-- 
✅ What is a Session?
A session lets you store data across multiple pages for the same user — like:

keeping a user logged in

saving their cart

remembering their preferences

PHP creates a unique session ID for each visitor and stores it on the server. 
-->

<?php
    
    require_once 'config.php';

    // session_start();
    $_SESSION["username"] = "Shree";
    // // unset($_SESSION["username"]);
    // // session_unset();
    // // session_destroy();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php  
        echo $_SESSION["username"];
    ?>
</body>
</html>
