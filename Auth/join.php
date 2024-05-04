<?php
$wu = 0;
$wp = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $userPW = $_POST['userPW'];
    $userName = $_POST['userName'];
    $userEmail = $_POST['userEmail'];
    $verificationCode = isset($_POST['verify']) ? $_POST['verify'] : '';

    if (!empty($userID) && !empty($userPW) && !empty($userName) && !empty($userEmail) && !empty($verificationCode)) {
        // ... (이메일 인증 및 회원가입 처리는 그대로 유지)
       // 이메일 인증 및 회원가입 처리

        // 데이터베이스 연결 정보
        $db_id = "  ";
        $db_pw = "  ";
        $db_name = "  ";
        $db_domain = "  ";

        $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name);
        if (!$conn) {
            die("데이터베이스 연결 실패: " . mysqli_connect_error());
        }

        // 사용자 이름 중복 검사
        $sql = "SELECT userID FROM user WHERE userID = '$userID';";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $wu = 1;
        } else {
            // 데이터베이스에서 인증 코드 확인
            $sql_verify = "SELECT confirm FROM chec WHERE userID = '$userID' AND userEmail = '$userEmail';";
            $result_verify = mysqli_query($conn, $sql_verify);
            $row_verify = mysqli_fetch_assoc($result_verify);

            if ($row_verify && $row_verify['confirm'] == $verificationCode) {
                // 인증 코드 일치하면 회원 정보를 데이터베이스에 저장하는 SQL 쿼리
                $hashed_password = password_hash($userPW, PASSWORD_DEFAULT);
                $dbconn = "INSERT INTO user (userID, userPW, userName, userEmail) VALUES ('$userID', '$hashed_password', '$userName', '$userEmail');";
                if (mysqli_query($conn, $dbconn)) {
                    mysqli_close($conn);
                    echo "<script>alert('회원가입 성공.'); window.location.href='login.php';</script>";
                } else {
                    echo "<p>회원가입 중 오류가 발생했습니다: " . mysqli_error($conn) . "</p>";
                }
            } else {
                echo "<p>인증 코드가 일치하지 않습니다. 다시 확인해주세요.</p>";
            }
        }

        mysqli_close($conn);
    }
}
?>

<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>join</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        input, button { font-family: inherit; font-size: inherit; }
        .login-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <h2>회원 가입</h2>
    <form method="post" id="login-form">
        <p><input type="text" name="userID" placeholder="ID" required></p>
        <p><input type="password" name="userPW" placeholder="Password" required></p>
        <p><input type="text" name="userName" placeholder="Nickname" required></p>
        <input type="text" name="userEmail" placeholder="Email" required>
        <p><button id="Emailbtn" type="button" onclick="sendVerificationEmail();">이메일 인증</button></p>
        <input type="text" id="verificationCode" name="verify" placeholder="인증번호" style="display: block;" value="" required>
        <p><button type="submit" id="submitButton" style="display: block;" formaction="join.php">회원 가입</button></p>
        <?php
        if ($wu == 1) {
            echo "<p>사용자이름이 중복되었습니다.</p>";
        }
        if ($wp == 1) {
            echo "<p>비밀번호가 일치하지 않습니다.</p>";
        }
        ?>
    </form>
</div>

<script>
    function sendVerificationEmail() {
        const userEmail = document.getElementsByName('userEmail')[0].value;
        const userID = document.getElementsByName('userID')[0].value; // 이 부분을 추가

        if (!userEmail || !userID) {
            alert('ID와 이메일을 입력해주세요');
            return;
        }

        // 이메일 인증 버튼 클릭 후 서버로 요청하여 이메일 인증 코드 전송
        fetch('emailer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'to_email=' + userEmail + '&to_userID=' + userID, // 이 부분을 추가 ('&to_userID=' + userID)
        })
            .then(response => response.text())
            .then(data => {                
                document.getElementById('verificationCode').style.display = 'block';
                document.getElementById('submitButton').style.display = 'block'; // 회원 가입 버튼 활성화
                document.getElementById('Emailbtn').style.display = 'none'; // 이메일 인증 버튼 감추기
                alert('이메일이 성공적으로 전송되었습니다. 이메일을 확인해주세요.');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('이메일 발송에 실패했습니다. 다시 시도해주세요.');
            });
    }

</script>
</body>
</html>
