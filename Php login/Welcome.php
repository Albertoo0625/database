<?php

require_once "connection.php";
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <link rel="stylesheet" href="./stylez.css" />
</head>
<body>
   <div class="rick">
       <p>this is an image test</p>
       </div> 
      <?php
            echo "<h1> Welcome User:" .$_SESSION['user']['name']."</h>";
            echo str_repeat('&nbsp;', 5);
            print_r($_SESSION);
            echo str_repeat('&nbsp;', 5);
         ?>
   
<br>
<br>
<br>
<br>
<br>
<br>

   <a class="btn btn-primary" href="logout.php">logout</a> 
</body>
</html>