<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <title></title>
</head>
<body>
   <?php
   session_start();
   $db_id = "  ";
   $db_pw = "  ";
   $db_name = "  ";
   $db_domain = "  ";

      $mysqli = new mysqli($db_domain, $db_id, $db_pw, $db_name); 
      
      $userID = $_POST['userID'];
      $userPW = $_POST['userPW'];
      $userName = $_POST['userName'];
      $userEmail = $_POST['userEmail'];
      
      $q = "SELECT userID FROM user WHERE userID = '$userID' AND userPW = '$userPW'";
      $result = $mysqli->query($q);
      $row = $result->fetch_array(MYSQLI_ASSOC);

      if ($row != null) {
         $_SESSION['username'] = $row['id'];
         $_SESSION['name'] = $row['name'];
         echo "<script>location.replace('index.php');</script>";
         exit;
      }
      
      if($row == null){
         echo "<script>alert('Invalid username or password')</script>";
         echo "<script>location.replace('login.php');</script>";
         exit;
      }
      ?>
   </body>
