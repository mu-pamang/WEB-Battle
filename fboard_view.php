<style>
.fboard_view_table {
border: 1px solid #444444;
margin-top: 30px;
width:100%;
}
.fboard_view_title {
height: 30px;
text-align: center;
background-color: #cccccc;
color: white;
width:100%;
}
.fboard_view_id {
text-align: center;
background-color: #EEEEEE;
width: 30px;
}
.fboard_view_id2 {
background-color: white;
width: 60px;
}
.fboard_view_hit {
background-color: #EEEEEE;
width: 30px;
text-align: center;
}
.fboard_view_hit2 {
background-color: white;
width: 60px;
}
.fboard_view_content {
padding-top: 20px;
border-top: 1px solid #444444;
height: 500px;
}
.fboard_view_btn {
width: 700px;
height: 200px;
text-align: center;
margin: auto;
margin-top: 50px;
}
.fboard_view_btn1 {
height: 50px;
width: 100px;
font-size: 20px;
text-align: center;
background-color: white;
border: 2px solid black;
border-radius: 10px;
}
.fboard_view_comment_input {
width: 700px;
height: 500px;
text-align: center;
margin: auto;
}
.fboard_view_text3 {
font-weight: bold;
float: left;
margin-left: 20px;
}
.fboard_view_com_id {
width: 100px;
}
.fboard_view_comment {
width: 500px;
}
.fboard_view_btn_recommend {
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
ini_set('display_errors','0');
$connect = mysqli_connect("  ", "  ", "  ", "  ");
$number = $_GET['number']; // 수정: POST 대신 GET 파라미터로 받아옴
session_start();
$query = "SELECT title, content, date, hit, id, filename, filesize, filetype FROM freeno WHERE number = $number";
$result = $connect->query($query);
$rows = mysqli_fetch_assoc($result);

// 두번쩨 조회수 갱신 로직
$ip = $_SERVER['REMOTE_ADDR'];
$current_time = date('Y-m-d H:i:s');
$hour_ago = date('Y-m-d H:i:s', strtotime('+1 hour'));

$last_view_query = "SELECT * FROM freenoren WHERE userIP = '$ip' AND boardID = $number ORDER BY date DESC LIMIT 1";
$last_view_result = $connect->query($last_view_query);

$last_date = $last_view_result->fetch_assoc()['date'];

$last_view_row = mysqli_num_rows($last_view_result);

$afterhour = date('Y-m-d H:i:s', strtotime('+1 hours'));

if (($last_view_row === 0) || (strtotime($current_time) > strtotime($last_date))) {
    // 사용자가 컨텐츠를 1시간 이내에 열람하지 않은 경우, 조회수를 증가시키고 noteren 테이블에 열람 정보를 추가
    $connect->query("UPDATE freeno SET hit = hit + 1 WHERE number = $number");
    $connect->query("INSERT INTO freenoren (userIP, boardID, date) VALUES ('$ip', $number, '$afterhour')");
} else {
    // 사용자가 컨텐츠를 1시간 이내에 이미 열람한 경우, 조회수를 다시 증가시키지 않고 아무런 추가 동작을 수행하지 X
}

// 사용자가 로그인한 경우에만 admin 여부를 판단
if (isset($_SESSION['userID'])) {
    $user_id = $_SESSION['userID'];
    $author_id = $rows['id'];

    // 데이터베이스 연결
    $conn = mysqli_connect("  ", "  ", "  ", "  ") or die("connect fail");

    // userID를 이용하여 'user' 테이블에서 해당 사용자의 roll 값을 가져옴
    $query = "SELECT roll FROM user WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userRoll);
    mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);

    mysqli_close($conn);

    $allowModify = ($user_id === $author_id);

    $allowDeleteAll = ($userRoll === 'admin');
} else {
    $allowModify = false;
    $allowDeleteAll = false;
}

$query = "SELECT COUNT(*) AS recommend_count FROM good WHERE boardID = '$number'";
$result = $connect->query($query);
$row = mysqli_fetch_assoc($result);
$recommend_count = $row['recommend_count'];

// 사용자가 이미 이 게시물을 추천했는지 확인합니다.
$alreadyRecommended = false;
if (isset($_SESSION['userID'])) {
    $user_id = $_SESSION['userID'];

    // 데이터베이스 연결
    $conn = mysqli_connect('localhost', 'root', '_pd/_9cp29dQ', 'free') or die("connect fail");

    $query = "SELECT * FROM good WHERE userID = '$user_id' AND boardID = '$number'";
    $result = $conn->query($query);
    $alreadyRecommended = (mysqli_num_rows($result) > 0);
    mysqli_close($conn);
}

// 게시물 추천 기능 처리
if (isset($_SESSION['userID']) && isset($_GET['action'])) {
    if ($_GET['action'] === 'recommend') {
        $user_id = $_SESSION['userID'];
        $post_id = $number;

        $conn = mysqli_connect("  ", "  ", "  ", "  ") or die("connect fail");

        // 이미 따봉한 경우, 따봉 취소 처리
        $query = "SELECT * FROM good WHERE userID = '$user_id' AND boardID = '$post_id'";
        $result = $conn->query($query);

        if (mysqli_num_rows($result) > 0) {
            // 따봉 취소: 'good' 테이블에서 해당 사용자와 게시물 정보 삭제
            $query = "DELETE FROM good WHERE userID = '$user_id' AND boardID = '$post_id'";
            $result = $conn->query($query);
        } else {
            // 따봉 : 'good' 테이블에 해당 사용자와 게시물 정보 추가
            $query = "INSERT INTO good (userID, boardID) VALUES ('$user_id', '$post_id')";
            $result = $conn->query($query);
        }

        mysqli_close($conn);

        header("Location: fboard_view.php?number=$number");
        exit;
    }
}
?>

<table class="fboard_view_table" align="center">
    <tr>
        <td colspan="6" class="fboard_view_title"><?php echo $rows['title'] ?></td>
    </tr>
    <tr>
        <td class="fboard_view_id">작성자</td>
        <td class="fboard_view_id2"><?php echo $author_id ?></td>
        <td class="fboard_view_hit">조회수</td>
        <td class="fboard_view_hit2"><?php echo $rows['hit'] ?></td>
        <td class="fboard_view_hit">추천수</td>
        <td class="fboard_view_hit2"><?php echo $recommend_count ?></td>
    </tr>
    <tr>
        <td colspan="6" class="fboard_view_content" valign="top">
            <?php echo $rows['content'] ?>
        </td>
        <td class="fboard_view_hit2" style="background-color: white; width: 100px; text-align: center;"></td>
    </tr>
    
    <td>
        <?php if (!empty($rows['filename'])) : ?>
            <p>첨부 파일: <a href="download.php?filename=<?php echo urlencode($rows['filename']); ?>&size=<?php echo $rows['filesize']; ?>&type=<?php echo urlencode($rows['filetype']); ?>"><?php echo $rows['filename']; ?></a></p>
        <?php else: ?>
            파일 없음
        <?php endif; ?>
        </td>
</table>

<!-- MODIFY & DELETE -->
<div class="fboard_view_btn">
    <button class="fboard_view_btn1" onclick="location.href='./fboard.php'">목록으로</button>
    <?php if ($allowModify) : ?>
        <button class="fboard_view_btn1" onclick="location.href='./fboard_modify.php?number=<?php echo $number ?>'">수정</button>
        <?php if ($allowDeleteAll || $allowModify) : ?>
            <button class="fboard_view_btn1" onclick="location.href='./fboard_delete.php?number=<?php echo $number ?>'">삭제</button>
        <?php endif; ?>
    <?php else: ?>
        <!-- admin 권한인 경우 모든 게시물에 삭제 버튼이 뜨도록 설정 -->
        <?php if ($allowDeleteAll) : ?>
            <button class="fboard_view_btn1" onclick="location.href='./fboard_delete.php?number=<?php echo $number ?>'">삭제</button>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!$alreadyRecommended) : ?>
        <!-- 게시물 추천 버튼을 클릭한 경우, 현재 페이지에 대한 action=recommend 매개변수를 추가 -->
        <button class="fboard_view_btn1" onclick="location.href='./fboard_view.php?number=<?php echo $number ?>&action=recommend'">추천</button>
    <?php else : ?>
        <!-- 이미 추천한 경우, 추천 취소를 위해 action=recommend_cancel 매개변수를 추가 -->
        <button class="fboard_view_btn1" onclick="location.href='./fboard_view.php?number=<?php echo $number ?>&action=recommend_cancel'">추천취소</button>
    <?php endif; ?>
    <button class="fboard_view_btn1" onclick="location.href='./fboard_comment.php?number=<?php echo $number ?>'">댓글</button>
</div>
</div>

<?php
// 게시물 추천 취소 기능 처리
if (isset($_SESSION['userID']) && isset($_GET['action'])) {
    if ($_GET['action'] === 'recommend_cancel') {
        $user_id = $_SESSION['userID'];
        $post_id = $number;

        $conn = mysqli_connect("  ", "  ", "  ", "  ") or die("connect fail");

        // 따봉 취소: 'good' 테이블에서 해당 사용자와 게시물 정보 삭제
        $query = "DELETE FROM good WHERE userID = '$user_id' AND boardID = '$post_id'";
        $result = $conn->query($query);

        mysqli_close($conn);

        header("Location: fboard_view.php?number=$number");
        exit;
    }
}
?>
