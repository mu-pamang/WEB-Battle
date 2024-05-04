<?php
   $host = "  ";
   $username = "  ";
   $password = "  ";
   $database = "  ";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("  ", "  ", "  ", "  ");

    if ($conn->connect_error) {
        die("데이터베이스 연결 실패: " . $conn->connect_error);
    }

    $comment_number = $_POST['comment_number'];

    $boardID = $_POST['boardID'];

    session_start();
    $current_user_id = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
    $current_user_roll = isset($_SESSION['roll']) ? $_SESSION['roll'] : null;

    // 댓글 작성자의 ID 가져오기
    $query = "SELECT userID FROM comment WHERE number = $comment_number";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $comment_writer_id = $row['userID'];

    // 로그인한 사용자가 댓글 작성자 or 관리자 경우 댓글 삭제 처리
    if ($current_user_id === $comment_writer_id || $current_user_roll === 'admin') {
        $delete_query = "UPDATE comment SET content = '삭제된 댓글입니다.',userID = '무파망' WHERE number = $comment_number";
        if ($conn->query($delete_query) === TRUE) {
            echo "댓글이 성공적으로 삭제되었습니다.";
        } else {
            echo "댓글 삭제 중 오류가 발생하였습니다: " . $conn->error;
        }
    } else {
        echo "댓글 삭제 권한이 없습니다.";
    }

    $conn->close();

    header("Location: fboard_comment.php?number=$boardID");
    exit;
}
?>
