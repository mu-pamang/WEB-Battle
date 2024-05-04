<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendVerificationEmail($to_email, $auth_code) {
    $gmail_user_name = '@gmail.com'; 
    $gmail_app_password = ''; 

    $from_name = '윤혜준';
    $from_email = '@gmail.com';

    $mail = new PHPMailer(true);
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    try {
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";
        $mail->Username = $gmail_user_name;
        $mail->Password = $gmail_app_password;
        $mail->CharSet = 'utf-8';
        $mail->Encoding = "base64";

        $mail->setFrom($from_email, $from_name);
        $mail->AddAddress($to_email, "nowonbun");

        $mail->isHTML(true);
        $mail->Subject = '이메일 인증 코드';
        $mail->Body = '이메일 인증 코드: <b>' . $auth_code . '</b>';
        $mail->AltBody = '이메일 인증 코드: ' . $auth_code;
        $mail->Send();

        return true;
    } catch (Exception $e) {
        return false;
    }
}

// emailer.php 파일이 직접 실행되지 않도록 방지
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $to_email = $_POST['to_email'] ?? null;
  $to_userID = $_POST['to_userID'] ?? null;

  if ($to_email && filter_var($to_email, FILTER_VALIDATE_EMAIL) && $to_userID) { 
      $auth_code = rand(100000, 999999); // 6자리 임의의 인증 코드 생성
      $_SESSION['auth_code'] = array('code' => $auth_code);

      if (sendVerificationEmail($to_email, $auth_code)) {
           $db_id = "  ";
           $db_pw = "  ";
           $db_name = "  ";
           $db_domain = "  ";


          $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name);
          if (!$conn) {
              die("데이터베이스 연결 실패: " . mysqli_connect_error());
          }

          $sql_insert = "INSERT INTO chec (userID, userEmail, confirm) VALUES ('$to_userID', '$to_email', '$auth_code');";
          if (mysqli_query($conn, $sql_insert)) {
              echo "Message has been sent";
          } else {
              echo "Failed to save the verification code";
          }

          mysqli_close($conn);
      } else {
          echo "Failed to send the message";
      }
  } else {
      echo "이메일 주소나 사용자 ID가 유효하지 않습니다";
  }
}
?>
