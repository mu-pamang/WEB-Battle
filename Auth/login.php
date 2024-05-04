<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$wu = 0; 
$wp = 0; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $userPW = $_POST['userPW'];

    if (!empty($userID) && !empty($userPW)) {
        $db_id = "  ";
        $db_pw = "  ";
        $db_name = "  ";
        $db_domain = "  t";

        $conn = mysqli_connect($db_domain, $db_id, $db_pw, $db_name);

        if (mysqli_connect_errno()) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT userPW, roll FROM user WHERE userID = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            die("Query prepare failed: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $userID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result) {
            die("Query execution failed: " . mysqli_error($conn));
        }

        $hashed_password = null;
        $userRoll = null;
        while ($row = mysqli_fetch_array($result)) {
            $hashed_password = $row['userPW'];
            $userRoll = $row['roll'];
        }

        if (is_null($hashed_password)) {
            $wu = 1; // 아이디가 존재하지 않음
        } else {
            // 비밀번호 일치 여부 확인 (password_verify 함수 사용)
            if (password_verify($userPW, $hashed_password)) {
                // 로그인 성공
                session_start();
                $_SESSION['roll'] = $userRoll;
                $_SESSION['userID'] = $userID;
                echo "<script>alert('로그인 성공.'); window.location.href='index2.php';</script>";
                exit;
            } else {
                $wp = 1; // 비밀번호가 틀림
            }
        }
    }
}
?>

<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="CSS/style_login.css">
    <title>로그인</title>
    <style>
        body { font-family: sans-serif; font-size: 20px; }
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
        <h1>로그인</h1>
        <form action="login.php" method="POST">
            <p><input type="text" name="userID" placeholder="ID" required></p>
            <p><input type="password" name="userPW" placeholder="Password" required></p>
            <p><input type="submit" value="로그인"></p>
            <?php
            if ($wu == 1) {
                echo "<p>ID가 존재하지 않습니다.</p>";
            }
            if ($wp == 1) {
                echo "<p>비밀번호가 틀렸습니다.</p>";
            }
            ?>
        </form>
    </div>
</body>
</html>
