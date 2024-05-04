<?php
   $host = "  ";
   $username = "  ";
   $password = "  ";
   $database = "  ";

// 댓글 삭제 처리
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("  ", "  ", "  ", "  ");

    if ($conn->connect_error) {
        die("데이터베이스 연결 실패: " . $conn->connect_error);
    }

    // 댓글 식별자(commentID)를 POST로부터 가져옴
    $comment_number = $_POST['comment_number'];

    // 게시글 식별자(boardID)를 POST로부터 가져옴
    $boardID = $_POST['boardID'];

    // 현재 로그인한 사용자의 ID와 권한
    session_start();
    $current_user_id = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
    $current_user_roll = isset($_SESSION['roll']) ? $_SESSION['roll'] : null;

    // 댓글 작성자의 ID 가져오기
    $query = "SELECT userID FROM qcomment WHERE number = $comment_number";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $comment_writer_id = $row['userID'];

    // 로그인한 사용자가 댓글 작성자이거나 관리자인 경우에만 댓글 삭제 처리
    if ($current_user_id === $comment_writer_id || $current_user_roll === 'admin') {
        // 댓글 삭제
        $delete_query = "UPDATE qcomment SET content = '삭제된 답변입니다.',userID = '무파망' WHERE number = $comment_number";
        $delete_query = "DELETE FROM qcomment WHERE number = $comment_number";
        if ($conn->query($delete_query) === TRUE) {
            echo "댓글이 성공적으로 삭제되었습니다.";
        } else {
            echo "댓글 삭제 중 오류가 발생하였습니다: " . $conn->error;
        }
    } else {
        echo "댓글 삭제 권한이 없습니다.";
    }

    $conn->close();

    // 댓글 삭제 후, 게시글 상세보기 페이지로 이동
    header("Location: qna_comment.php?number=$boardID");
    exit;
}
?>
