<!DOCTYPE html>
<html>
<head>
    <title>댓글</title>
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
    <h2>댓글 작성</h2>
    <div class="comment-container">
        <form action="fboard_comment_write.php" method="post">
            <?php
            session_start();
            if (isset($_SESSION['userID'])) {
                $user_id = $_SESSION['userID'];
                $board_id = $_GET['number'];
                echo "<div class='user-input'>";
                echo "<label class='user-id-label'>작성자:</label> <input type='text' name='user_id' value='$user_id' readonly>";
                echo "<input type='hidden' name='boardID' value='$board_id'>";
               ?>
               
               <?php
                echo "</div>";
            } else {
                echo "<div class='user-input'>";
                echo "<label class='user-id-label'>작성자:</label> <input type='text' name='user_id' placeholder='사용자ID' required>";
                echo "</div>";
            }
            ?>
            <textarea name="content" placeholder="댓글 내용" required></textarea>
            <input type="submit" value="댓글 작성" class="btn">
        </form>
    </div>

    <h2>댓글 목록</h2>
    <div class="comment-container">

    <?php
        $host = "localhost";
        $username = "root";
        $password = "_pd/_9cp29dQ";
        $database = "free";

        $conn = new mysqli('localhost', 'root', '_pd/_9cp29dQ', 'free');

        if ($conn->connect_error) {
            die("데이터베이스 연결 실패: " . $conn->connect_error);
        }

        if (isset($_GET['number'])) {
            $boardID = $_GET['number'];

            $sql = "SELECT * FROM freeno WHERE number = $boardID";
            $board_regi=($conn->query($sql));
            $board_writer = $board_regi->fetch_assoc()['id'];
            
            $sql = "SELECT * FROM comment WHERE boardID = $boardID ORDER BY date DESC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $comment_number = $row["number"];
                    $user_id = $row["userID"];
                    $comment_text = $row["content"];
                    $created_at = $row["date"];

                    $current_user_id = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
                    $current_user_roll = isset($_SESSION['roll']) ? $_SESSION['roll'] : null;
                    
                    // 로그인한 사용자의 ID와 댓글 작성자의 ID를 비교하여 삭제 버튼을 표시할지 결정
                    $can_delete_comment = false;
                    if ($current_user_id === $user_id) {
                        $can_delete_comment = true;
                    } elseif ($current_user_roll === 'admin') {
                        $can_delete_comment = true; 
                    }elseif ($current_user_id === $board_writer) {
                        $can_delete_comment = true; 
                    }
                    echo "<div class='comment'>";
                    echo "<strong>$user_id</strong> | $created_at<br>";
                    echo "$comment_text";

                    if($user_id === '무파망'){
                        $can_delete_comment = false;
                    }

                    // 수정 버튼 표시 조건에 맞을 경우에만 수정 버튼을 표시
                    if ($current_user_id === $user_id) {
                        
                        echo "<form action='fboard_comsu.php' method='post'>";
                        echo "<input type='hidden' name='comment_number' value='$comment_number'>";
                        echo "<input type='hidden' name='content' value='$comment_text'>";
                        echo "<input type='hidden' name='boardID' value='$boardID'>";
                        echo "<input type='submit' value='댓글 수정' class='btn'>";
                        echo "</form>";
                        
                    }
                    if ($can_delete_comment) {
                        // 댓글 수정 폼 HTML 코드
                        // echo "<div class='comment'>";
                        // echo "<strong>$user_id</strong> | $created_at<br>";
                        // echo "$comment_text";
                        
                        echo "<form action='fboard_comdel.php' method='post'>";
                        echo "<input type='hidden' name='comment_number' value='$comment_number'>";
                        echo "<input type='hidden' name='boardID' value='$boardID'>";
                        echo "<input type='submit' value='댓글 삭제' class='btn'>";
                        echo "</form>";
                        
                    }

                    echo "</div>";
                        
                }
            } else {
                echo "<div class='no-comments'>등록된 댓글이 없습니다.</div>";
            }
        } else {
            echo "<div class='no-comments'>댓글을 볼 게시글을 선택하세요.</div>";
        }

        $conn->close();
        ?>
    </div>

    <a href="fboard.php" class="btn">게시글로 돌아가기</a>
    <script>
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
