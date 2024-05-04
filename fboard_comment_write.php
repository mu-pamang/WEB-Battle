<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_id"]) && isset($_POST["content"]) && isset($_POST['boardID'])) {

   $host = "  ";
   $username = "  ";
   $password = "  ";
   $database = "  ";
$conn = new mysqli("  ", "  ", "  ", "  ");

if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_id"]) && isset($_POST["content"]) && isset($_POST['boardID'])) {
    $user_id = $_POST["user_id"];
    $comment_text = $_POST["content"];
    $board_id = !empty($_POST['boardID']) ? $_POST['boardID'] : 0; 
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO comment (userID, boardID, content, date) VALUES ('$user_id', '$board_id', '$comment_text', '$created_at')";
    if ($conn->query($sql) === TRUE) {
        header("Location: fboard_comment.php?number=$board_id");
        exit();
    } else {
        echo "댓글 작성에 실패했습니다.";
    }
}

$conn->close();
}
?>
