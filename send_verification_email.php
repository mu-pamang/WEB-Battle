<?php
// 이메일 인증을 위해 사용자에게 인증 코드를 보내는 함수 (emailer.php에서 가져옴)
function sendVerificationEmail($email, $verificationCode) {
    // 이 함수에서 이메일을 발송하는 코드 (emailer.php 파일 내부의 함수와 동일)
}

// 랜덤한 인증 코드 생성 함수 (join.php에서 가져옴)
function generateRandomCode() {
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userEmail = $_POST['userEmail'];
    $verificationCode = generateRandomCode(); // 이 함수는 join.php 파일에서 가져옴

    // 이메일 발송 함수 호출 (emailer.php에 정의된 함수)
    sendVerificationEmail($userEmail, $verificationCode);

    // 이메일 발송 성공 여부를 클라이언트로 응답합니다.
    echo "인증 메일이 발송되었습니다. 이메일을 확인해주세요.";
  }
?>
