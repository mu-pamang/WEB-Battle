<?php
$host = "  ";
$username = "  ";
$password = "  ";
$database = "  ";

$conn = new mysqli("  ", "  ", "  ", "  ");

if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// 댓글 작성 폼에서 전달된 데이터를 변수에 저장
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["content"]) && isset($_POST["comment_number"]) && isset($_POST['boardID'])) {
    
    $comment_text = $_POST["content"];
    $comment_number = $_POST["comment_number"];
    $board_id = !empty($_POST['boardID']) ? $_POST['boardID'] : 0; // 빈 값일 경우 0 또는 적절한 기본값 설정
    $created_at = date('Y-m-d H:i:s');

    // 데이터베이스에 댓글 정보를 저장
    $sql = "UPDATE qcomment SET content='$comment_text' WHERE number=$comment_number";
    var_dump($sql);
    if ($conn->query($sql) === TRUE) {
        // 댓글 등록이 성공적으로 이루어지면, 댓글 작성 페이지(fboard_comment.php)로 리다이렉션
        header("Location: qna_comment.php?number=$board_id");
        exit();
    } else {
        echo "답글 작성에 실패했습니다.";
    }
}

$conn->close();

?>
