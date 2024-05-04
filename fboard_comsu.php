<?php
$host = "  ";
$username = "  ";
$password = "  ";
$database = "  ";

$conn = new mysqli("  ", "  ", "  ", "  ");

if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// 댓글 수정 폼에서 전달된 데이터를 변수에 저장
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comment_number"]) && isset($_POST["content"]) && isset($_POST['boardID'])) {
    $comment_number = $_POST["comment_number"];
    $comment_text = $_POST["content"];
    $boardID = !empty($_POST['boardID']) ? $_POST['boardID'] : 0; // 빈 값일 경우 0 또는 적절한 기본값 설정

    // 댓글 수정 폼
    echo "<form action='fboard_comsu_ok.php' method='post'>";
    echo "<textarea name='content' required>$comment_text</textarea>";
    echo "<input type='hidden' name='comment_number' value='$comment_number'>";
    echo "<input type='hidden' name='boardID' value='$boardID'>";
    echo "<input type='submit' value='댓글 수정' class='btn'>";
    echo "</form>";

}

$conn->close();
?>
