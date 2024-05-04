<?php

// 댓글 작성 폼에서 전달된 데이터를 변수에 저장
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_id"]) && isset($_POST["content"]) && isset($_POST['boardID'])) {
    $user_id = $_POST["user_id"];
    $comment_text = $_POST["content"];
    $board_id = !empty($_POST['boardID']) ? (int)$_POST['boardID'] : 0; // 빈 값일 경우 0 또는 적절한 기본값 설정
    $created_at = date('Y-m-d H:i:s');
    var_dump($board_id);
    // 데이터베이스에 댓글 정보를 저장


    // 데이터베이스 연결 설정
     $host = "  ";
     $username = "  ";
     $password = "  ";
     $database = "  ";

    $conn = new mysqli("  ", "  ", "  ", "  ");

    if ($conn->connect_error) {
        die("데이터베이스 연결 실패: " . $conn->connect_error);
    }
    if(!empty ($_FILES["file_upload"]["name"])){
        $target_dir = "/uploads/"; // 업로드된 파일이 저장될 디렉토리 경로 (프로젝트 폴더 기준)
        $filename = $_FILES["file_upload"]["name"]; // 업로드한 파일의 원본 파일명
        $filetype = $_FILES["file_upload"]["type"]; // 업로드한 파일의 MIME 타입
        $filesize = $_FILES["file_upload"]["size"]; // 업로드한 파일의 크기 (바이트 단위)
        $filetmp = $_FILES["file_upload"]["tmp_name"]; // 업로드된 파일이 임시로 저장된 경로

    // 파일을 업로드할 디렉토리와 파일명 설정
    $target_file = $target_dir . ($filename);

    // 업로드된 파일을 지정한 디렉토리로 이동
    if (move_uploaded_file($filetmp, $target_file)) {
        // 파일 업로드가 성공한 경우, 데이터베이스에 파일 정보를 저장
        $sql = "INSERT INTO qcomment (userID, boardID, content, date, filename, filetype, filesize) VALUES ('$user_id', $board_id, '$comment_text', '$created_at', '$filename', '$filetype', '$filesize')";
        if ($conn->query($sql) === TRUE) {
            // 댓글 등록이 성공적으로 이루어지면, 댓글 작성 페이지(fboard_comment.php)로 리다이렉션
            header("Location: qna_comment.php?number=$board_id");
            exit();
        } else {
            echo "댓글 작성에 실패했습니다.";
        }
    } else {
        echo "파일 업로드에 실패했습니다.";
    }
    }else{
        $sql = "INSERT INTO qcomment (userID, boardID, content, date, filename, filetype, filesize) VALUES ('$user_id', $board_id, '$comment_text', '$created_at', '', '', 0)";
        if ($conn->query($sql) === TRUE) {
            // 댓글 등록이 성공적으로 이루어지면, 댓글 작성 페이지(fboard_comment.php)로 리다이렉션
            header("Location: qna_comment.php?number=$board_id");
            exit();
        } else {
            echo "댓글 작성에 실패했습니다.";
        }
    }

    $conn->close();
}
?>
