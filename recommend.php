<?php
  session_start();
  
  if (!isset($_SESSION['userID'])) {
      // 로그인되어 있지 않은 경우, 로그인 페이지로 리다이렉트
      header('Location: login.php');
      exit();
  }
  
  $connect = mysqli_connect('localhost', '  ', '  ', '  ');
  $number = $_GET['number'];
  $user_id = $_SESSION['userID'];
  
  // 이미 추천한 게시물인지 확인
  $query = "SELECT * FROM good WHERE userID = '$user_id' AND boardID = '$number'";
  $result = $connect->query($query);
  
  if (mysqli_num_rows($result) === 0) {
      // 추천하지 않은 게시물이라면 추천 기록 추가
      $insert_query = "INSERT INTO good (userID, boardID) VALUES ('$user_id', '$number')";
      $connect->query($insert_query);
  
      // 해당 게시물의 추천 수 증가
      $update_query = "UPDATE freeno SET recommend = recommend + 1 WHERE number = '$number'";
      $connect->query($update_query);
  }
  
  $connect->close();
  
  // 해당 게시물의 상세 페이지로 리다이렉트
  header("Location: fboard_view.php?number=$number");
  exit();
?>
