<?php
require_once 'connection.php';

session_start();

if(isset($_SESSION['user'])){
    header("location:welcome.php");
}

if(isset($_REQUEST['login_button'])){
    $email=filter_var(strtolower(($_REQUEST['email'])),FILTER_SANITIZE_EMAIL);
    $password=strip_tags($_REQUEST['password']);


if(empty($email)){
    $errMessage[] =('Must enter Email');
}else if(empty($password)){
    $errMessage[] =('Must enter password');
}else{
       
    try{
        $select_stmt=$db->prepare("SELECT * from users where email = :email limit 1");
        $select_stmt->execute([
            ':email' => $email
        ]);
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
    
        if($select_stmt->rowCount() > 0){
           if(password_verify($password,$row['password'])){
               $_SESSION['user']['name']= $row['name'];
               $_SESSION['user']['email']= $row['email'];
               $_SESSION['user']['id']= $row['id'];

               header("location: welcome.php");
           }
           else{
            $errMessage[]="Wrong email or Password";
        }
    }else
    {
            $errMessage[]="Wrong email or Password";
             }
     }catch(PDOEXECPEPTION $e){
        echo $e->getMessage();
    }
}
 
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
</head>
<body>
   <form action="index.php" method="POST">

   <?php 

         echo '<pre>';
          print_r($_SESSION);
         echo '</pre>';

    if(isset($_REQUEST['msg'])){
        echo "<p class= 'alert alert-warning'>".$_REQUEST['msg']."</p>";
    }

    if(isset($errMessage)){
        foreach ($errMessage as $loginerror){
        echo "<p class= 'alert danger-warning'>".$loginerror."</p>";
    }
}
    ?>
       EMAIL: <input type="email" name='email' placeholder="janedoe@gmail.com">
       <br>
       PASSWORD: <input type="password" name='password' placeholder="*******">
       <br>
    <button type="submit" name="login_button">login </button>
<br>
<br>
No account? <a href="register.php" target="_blank">Register instead </a>
   </form> 
</body>
</html>