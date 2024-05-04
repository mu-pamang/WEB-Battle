<style>
.qna_view_table {
    border: 1px solid #444444;
    margin-top: 30px;
    width:100%;
}

.qna_view_title {
    height: 30px;
    text-align: center;
    background-color: #cccccc;
    color: white;
    width: 100%;
}

.qna_view_id {
    text-align: center;
    background-color: #EEEEEE;
    width: 30px;
}

.qna_view_id2 {
    background-color: white;
    width: 60px;
}

.qna_view_hit {
    background-color: #EEEEEE;
    width: 30px;
    text-align: center;
}

.qna_view_hit2 {
    background-color: white;
    width: 60px;
}

.qna_view_content {
    padding-top: 20px;
    border-top: 1px solid #444444;
    height: 500px;
}

.qna_view_btn {
    width: 700px;
    height: 200px;
    text-align: center;
    margin: auto;
    margin-top: 50px;
}

.qna_view_btn1 {
    height: 50px;
    width: 100px;
    font-size: 20px;
    text-align: center;
    background-color: white;
    border: 2px solid black;
    border-radius: 10px;
}

.qna_view_comment_input {
    width: 700px;
    height: 500px;
    text-align: center;
    margin: auto;
}

.qna_view_text3 {
    font-weight: bold;
    float: left;
    margin-left: 20px;
}

.qna_view_com_id {
    width: 100px;
}

.qna_view_comment {
    width: 500px;
}

.qna_view_btn_recommend {
    height: 50px;
    width: 100px;
    font-size: 20px;
    text-align: center;
    background-color: white;
    border: 2px solid black;
    border-radius: 10px;
}
</style>

<?php
$connect = mysqli_connect('  ', '  ', '  ', '  ');
$number = $_GET['number']; // 수정: POST 대신 GET 파라미터로 받아옴
session_start();
$query = "SELECT title, content, date, userID, secret, filename, filesize, filetype FROM jilmun WHERE number = $number";
$result = $connect->query($query);
$rows = mysqli_fetch_assoc($result);
if ($rows == null) {
    echo '<script>
    history.back();
 </script>';
}

$query = "SELECT COUNT(*) as num_comments FROM qcomment WHERE boardID = $number";
$result = $connect->query($query);
$row = mysqli_fetch_assoc($result);
$num_comments = $row['num_comments'];
// var_dump();
// $query = "UPDATE freeno SET hit = hit + 1 WHERE number = $number";
// $connect->query($query);
$allowDeleteAll = false;
// 사용자가 로그인한 경우에만 admin 여부를 판단
if (isset($_SESSION['userID'])) {
    $user_id = $_SESSION['userID'];
    $author_id = $rows['userID'];

    // 데이터베이스 연결
    $conn = mysqli_connect('  ', '  ', '  ', '  ') or die("connect fail");

    // userID를 이용하여 'user' 테이블에서 해당 사용자의 roll 값을 가져옴
    $query = "SELECT roll FROM user WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userRoll);
    mysqli_stmt_fetch($stmt);

    // statement와 연결을 닫음
    mysqli_stmt_close($stmt);

    // 데이터베이스 연결을 닫음
    mysqli_close($conn);

    // 현재 로그인한 사용자의 ID와 게시물의 작성자 ID를 비교하여 자신의 글인지 확인
    $allowModify = ($user_id === $author_id);

    // roll 값이 'admin'인 경우 모든 게시물을 삭제 가능
    if (($user_id === $author_id) || ($userRoll === 'admin')) {
        $allowDeleteAll = true;
    }
} else {
    // 로그인하지 않은 사용자는 수정 및 삭제 버튼을 보여주지 않음
    echo "<script> location.href = '/';</script>";
    $allowModify = false;
    $allowDeleteAll = false;
}

$number = $_GET['number'];
$query = "SELECT title, content, date, userID, secret, filename, filesize, filetype FROM jilmun WHERE number = $number";
$result = $connect->query($query);
$rows = mysqli_fetch_assoc($result);

// 글이 비밀글인 경우에만 관리자(admin)나 작성자만 볼 수 있도록 설정
if ($rows['secret'] == 1) {
    if (isset($_SESSION['userID'])) {
        $user_id = $_SESSION['userID'];
        $author_id = $rows['userID'];


        // 데이터베이스 연결
        $conn = mysqli_connect('localhost', 'root', '_pd/_9cp29dQ', 'member') or die("connect fail");

        // userID를 이용하여 'user' 테이블에서 해당 사용자의 roll 값을 가져옴
        $query = "SELECT roll FROM user WHERE userID = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $userRoll);
        mysqli_stmt_fetch($stmt);

        // statement와 연결을 닫음
        mysqli_stmt_close($stmt);

        // 데이터베이스 연결을 닫음
        mysqli_close($conn);

        // 현재 로그인한 사용자의 ID와 게시물의 작성자 ID를 비교하여 자신의 글인지 확인
        $allowModify = ($user_id === $author_id);

        // roll 값이 'admin'인 경우 모든 게시물을 볼 수 있음
        $allowViewAll = ($userRoll === 'admin');

        // 글이 비밀글이면서 작성자가 아니고 관리자도 아니라면 볼 수 없음
        if (!$allowViewAll && !$allowModify) {
            die("비밀글은 작성자와 관리자만 볼 수 있습니다.");
        }
    } else {
        die("비밀글은 작성자와 관리자만 볼 수 있습니다.");
    }
}

?>

<table class="qna_view_table" align="center">
    <tr>
        <td colspan="6" class="qna_view_title"><?php echo $rows['title'] ?></td>
    </tr>
    <tr>
        <td class="qna_view_id">작성자</td>
        <td class="qna_view_id2"><?php echo $author_id ?></td>
    </tr>
    <tr>
        <td colspan="6" class="qna_view_content" valign="top">
          <?php echo $rows['content'] ?>
        </td>
        <td class="qna_view_hit2" style="background-color: white; width: 100px; text-align: center;"></td>
    </tr>
    <!--파일 다운로드 링크 추가-->
    <td>
        <?php if (!empty($rows['filename'])) : ?>
            <p>첨부 파일: <a href="qdownload.php?filename=<?php echo urlencode($rows['filename']); ?>&size=<?php echo $rows['filesize']; ?>&type=<?php echo urlencode($rows['filetype']); ?>"><?php echo $rows['filename']; ?></a></p>
        <?php else : ?>
            파일 없음
        <?php endif; ?>
    </td>
    </td>
</table>

<!-- MODIFY & DELETE -->
<div class="qna_view_btn">
    <button class="qna_view_btn1" onclick="location.href='./qna.php'">목록으로</button>
    <?php if ($allowModify) : ?>
        <?php if ($num_comments == 0) : ?>
            <!-- 댓글이 없는 경우에만 수정 버튼을 표시합니다. -->
            <button class="qna_view_btn1" onclick="location.href='./qna_modify.php?number=<?php echo $number ?>'">수정</button>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($allowDeleteAll) : ?>
        <?php if ($num_comments == 0) : ?>
            <!-- 댓글이 없는 경우에만 삭제 버튼을 표시합니다. -->
            <button class="qna_view_btn1" onclick="location.href='./qna_delete.php?number=<?php echo $number ?>'">삭제</button>
        <?php endif; ?>
    <?php endif; ?>

    <!-- 댓글 작성 버튼은 항상 표시됩니다. -->
    <button class="qna_view_btn1" onclick="location.href='./qna_comment.php?number=<?php echo $number ?>'">답글</button>
</div>

