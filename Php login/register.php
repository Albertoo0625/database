<?php
require_once 'connection.php';

session_start();


 if(isset($_SESSION['user'])){
        header("location:welcome.php");
 }



    if(isset($_REQUEST['register_btn'])){

         echo '<pre>';
          print_r($_REQUEST);
         echo '</pre>';


       $name=filter_var($_REQUEST['name'],FILTER_SANITIZE_STRING);
       $email=filter_var(strtolower($_REQUEST['email']),FILTER_SANITIZE_EMAIL);
       $password=strip_tags($_REQUEST['password']);
    }

if(empty($name)){
    $errorMsg[0][]='Name Required';
}


if(empty($email)){
    $errorMsg[1][]='Email Required';
}


if(empty($password)){
    $errorMsg[2][]='Password Required';
}

if(strlen($password)< 6){
    $errorMsg[2][]='Password Must be atleast 6 characters';
}


if(empty($errorMsg)){
    try{
    $select_stmt=$db-> prepare("SELECT email from users where email = :email");
    $select_stmt-> execute([':email' => $email]);
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

    if(($row['email'] == $email))
    {
        $errorMsg[1][] ='Email address already exists,please choose another or login instead';
    }else{
        $hashed_password = password_hash($password,PASSWORD_DEFAULT);
        $created = new DateTime();
        $created = $created->format('Y-m-d H:i:s');
           
        $insert_stmt = $db->prepare("insert into users (name,email,password,created) values(:name,:email,:password,:created)");
         if(
            $insert_stmt -> execute(
           [   
             ':name' => $name,
             ':email' => $email,
             ':password' => $hashed_password,
             ':created' => $created          
           ] 
            )
             ){
             header("location: index.php?msg=".urlencode('click the verification email'));
         }
    }

}catch(PDOExeption $e){
        $pdoError = $e-> getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register page</title>
    <link rel="stylesheet" href="C:\Users\ADMIN\Downloads\Compressed\bootstrap-4.0.0-dist\css\bootstrap.min.css">
</head>
<body>
 

   <form action="register.php" method="POST">

   <?php 
   if(isset($errorMsg[0])){
    foreach($errorMsg[0] as $nameErrors){
        echo "<p class ='small text-danger'>".$nameErrors."</p>";
        }
         }
         ?>

       Name: <input type="text" name='name' placeholder="janedoe">
       <br>

       
   <?php 
   if(isset($errorMsg[1])){
    foreach($errorMsg[1] as $emailErrors){
        echo "<p class ='small text-danger'>".$emailErrors."</p>";
        }
         }
         ?>
       EMAIL: <input type="email" name='email' placeholder="janedoe@gmail.com">
       <br>


       <?php 
   if(isset($errorMsg[2])){
    foreach($errorMsg[2] as $passwordErrors){
        echo "<p class ='small text-danger'>".$passwordErrors."</p>";
        }
         }
         ?>

       PASSWORD: <input type="password" name='password' placeholder="*******">
       <br>
       
    <button type="submit" name='register_btn' class="register button">Register </button>
<br>
<br>
Already have an account? <a href="index.php" target="_blank">login instead </a>
<br>
<a href="welcome.php" target="_blank">welcome</a>

   </form> 
</body>
</html>