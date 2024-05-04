<!DOCTYPE html>
<html>
<head>
    <title>답변</title>
    <style>
         body {
            font-family: Arial, sans-serif;
        }
        .comment-container {
            margin-bottom: 20px;
        }
        .comment {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #f1f1f1;
            color: #333;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #ddd;
        }
        .user-id-label {
            font-weight: bold;
            margin-right: 5px;
        }
        .comment-form textarea {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
            resize: vertical;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px;
        }
        .comment-form input[type="text"] {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px;
        }
        .comment-form input[type="text"]:focus,
        .comment-form textarea:focus {
            outline: none;
            border-color: #007BFF;
        }
        .comment-form .user-input {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .no-comments {
            margin-top: 20px;
            font-style: italic;
            color: #888;
        }
        .comment-form {
            display: none;
        }
        .edit-btn {
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <!-- 답변 작성 폼 -->
    <h2>답변 작성</h2>
    <div class="comment-container">
        <?php
        session_start();
        $current_user_roll = $_SESSION['roll'];
        if ($current_user_roll !== 'user') {
            // 로그인한 사용자의 roll이 user가 닌 경우에만 댓글 작성 폼을 표시
        ?>
            
            <form action="qna_comment_write.php" method="post" enctype='multipart/form-data'>
                <?php
                  if (isset($_SESSION['userID'])) {
                      $user_id = $_SESSION['userID'];
                      $board_id = $_GET['number'];
                      echo "<div class='user-input'>";
                      echo "<label class='user-id-label'>작성자:</label> <input type='text' name='user_id' value='$user_id' readonly>";
                      echo "<input type='hidden' name='boardID' value='$board_id'>";
                      echo "</div>";
                  } else {
                      echo "<div class='user-input'>";
                      echo "<label class='user-id-label'>작성자:</label> <input type='text' name='user_id' placeholder='사용자ID' required>";
                      echo "</div>";
                  }
                ?>
                <textarea name="content" placeholder="답변 내용" required></textarea>
                <!-- 파일 업로드 폼 -->
                <input type='hidden' name='comment_number' value='$comment_number'>
                <input type='hidden' name='boardID' value='<?=$board_id?>'>
                <input type='file' name='file_upload'>
                <input type="submit" value="답변 작성" class="btn">
            </form>
        <?php
        } else {
            // 일반 사용자인 경우 댓글 작성 폼을 표시X
         ?>
            <div class="no-comments">권한이 없습니다.</div>
    </div>

    <!-- 댓글 목록 -->
    <h2>답변 목록</h2>
    <div class="comment-container">
        <!-- 등록된 댓글 표시 -->
        <?php
        $host = "  ";
        $username = "  ";
        $password = "  ";
        $database = "  ";

        $conn = new mysqli("  ", "  ", "  ", "  ");

        if ($conn->connect_error) {
            die("데이터베이스 연결 실패: " . $conn->connect_error);
        }

        // 게시글 식별자(boardID)를 URL 매개변수로부터 가져오기
        if (isset($_GET['number'])) {
            $boardID = $_GET['number'];

            // 해당 게시글의 댓글 조회
            // $sql = "SELECT * FROM jilmun WHERE number = $boardID";
            // $board_regi=($conn->query($sql));
            // $board_writer = $board_regi->fetch_assoc();
            // var_dump($board_writer);
            $sql = "SELECT * FROM qcomment WHERE boardID = $boardID";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $comment_number = $row["number"];
                    $user_id = $row["userID"];
                    $comment_text = $row["content"];
                    $created_at = $row["date"];
                    $file_name = $row["filename"];

                    // 현재 로그인한 사용자의 ID와 권한
                    $current_user_id = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
                    $current_user_roll = isset($_SESSION['roll']) ? $_SESSION['roll'] : null;
                    
                    // 로그인한 사용자의 ID와 댓글 작성자의 ID를 비교하여 삭제 버튼을 표시할지 결정
                    $can_delete_comment = false;
                    if ($current_user_roll === 'admin') {
                        $can_delete_comment = true; // 작성자 본인이면 삭제 버튼을 허용
                    } elseif ($current_user_id === $user_id) {
                        $can_delete_comment = true; // 작성자 본인이면 삭제 버튼을 허용
                    }
                    
                    echo "<div class='comment'>";
                    echo "<strong>$user_id</strong> | $created_at<br>";
                    echo "$comment_text";
                    if($user_id === '무파망'){
                        $can_delete_comment = false;
                    }

                     // 파일 다운로드 링크 추가
                    if (!empty($file_name)) {
                        echo "<p>첨부 파일: <a href='cdownload.php?filename=" . urlencode($file_name) . "&size=" . $row['filesize'] . "&type=" . urlencode($row['filetype']) . "'>$file_name</a></p>";
                    } else {
                        echo "파일 없음";
                    }

                    // 수정 버튼 표시 조건에 맞을 경우에만 수정 버튼을 표시합니다.
                    if ($current_user_id === $user_id) {
                        
                        echo "<form action='qna_comsu.php' method='post'>";
                        echo "<input type='hidden' name='comment_number' value='$comment_number'>";
                        echo "<input type='hidden' name='content' value='$comment_text'>";
                        echo "<input type='hidden' name='boardID' value='$boardID'>";
                        echo "<input type='submit' value='답변 수정' class='btn'>";
                        echo "</form>";

                        

                    }
                    if ($can_delete_comment) {
                        // 댓글 수정 폼을 보여주기 위한 HTML 코드
                        // echo "<div class='comment'>";
                        // echo "<strong>$user_id</strong> | $created_at<br>";
                        // echo "$comment_text";
                        
                        echo "<form action='qna_comdel.php' method='post'>";
                        echo "<input type='hidden' name='comment_number' value='$comment_number'>";
                        echo "<input type='hidden' name='boardID' value='$boardID'>";
                        echo "<input type='submit' value='답변 삭제' class='btn'>";
                        echo "</form>";                     
                    }

                    echo "</div>";
                        
                }
            } else {
                echo "<div class='no-comments'>등록된 답변이 없습니다.</div>";
            }
        } else {
            echo "<div class='no-comments'>댓글을 볼 게시글을 선택하세요.</div>";
        }

        $conn->close();
        ?>
    </div>

    <a href="qna.php" class="btn">Q&A로 돌아가기</a>
    <script>
        // 댓글 수정 폼 토글 함수
        function toggleEditForm(commentNumber) {
            const editForm = document.getElementById(`edit-form-${commentNumber}`);
            if (editForm.style.display === "none") {
                editForm.style.display = "block";
            } else {
                editForm.style.display = "none";
            }
        }
    </script>
</body>
</html>
