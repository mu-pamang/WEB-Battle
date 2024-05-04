<?php
   $host = "  ";
   $username = "  ";
   $password = "  ";
   $database = "  ";

$conn = new mysqli("  ", "  ", "  ", "  ");

if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["content"]) && isset($_POST["comment_number"]) && isset($_POST['boardID'])) {
    
    $comment_text = $_POST["content"];
    $comment_number = $_POST["comment_number"];
    $board_id = !empty($_POST['boardID']) ? $_POST['boardID'] : 0; 
    $created_at = date('Y-m-d H:i:s');
    
    $sql = "UPDATE comment SET content='$comment_text' WHERE number=$comment_number";
    var_dump($sql);
    if ($conn->query($sql) === TRUE) {
        header("Location: fboard_comment.php?number=$board_id");
        exit();
    } else {
        echo "댓글 작성에 실패했습니다.";
    }
}

$conn->close();

?>
