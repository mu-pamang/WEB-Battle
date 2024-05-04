<style>
.board_view_table {
border: 1px solid #444444;
margin-top: 30px;
}
.board_view_title {
height: 30px;
text-align: center;
background-color: #cccccc;
color: white;
width: 1000px;
}
.board_view_id {
text-align: center;
background-color: #EEEEEE;
width: 30px;
}
.board_view_id2 {
background-color: white;
width: 60px;
}
.board_view_hit {
background-color: #EEEEEE;
width: 30px;
text-align: center;
}
.board_view_hit2 {
background-color: white;
width: 60px;
}
.board_view_content {
padding-top: 20px;
border-top: 1px solid #444444;
height: 500px;
}
.board_view_btn {
width: 700px;
height: 200px;
text-align: center;
margin: auto;
margin-top: 50px;
}
.board_view_btn1 {
height: 50px;
width: 100px;
font-size: 20px;
text-align: center;
background-color: white;
border: 2px solid black;
border-radius: 10px;
}
.board_view_comment_input {
width: 700px;
height: 500px;
text-align: center;
margin: auto;
}
.board_view_text3 {
font-weight: bold;
float: left;
margin-left: 20px;
}
.board_view_com_id {
width: 100px;
}
.board_view_comment {
width: 500px;
}
</style>

<?php
ini_set('display_errors','0');
$connect = mysqli_connect("  ", "  ", "  ", "  ");
$number = $_GET['number']; 
session_start();
$query = "SELECT title, content, date, hit, id, filename, filesize, filetype from note where number = $number";
$result = $connect->query($query);
$rows = mysqli_fetch_assoc($result);

$ip = $_SERVER['REMOTE_ADDR'];
$current_time = date('Y-m-d H:i:s');
$hour_ago = date('Y-m-d H:i:s', strtotime('+1 hour'));

$last_view_query = "SELECT * FROM noteren WHERE userIP = '$ip' AND boardID = $number ORDER BY date DESC LIMIT 1";
$last_view_result = $connect->query($last_view_query);

$last_date = $last_view_result->fetch_assoc()['date'];

$last_view_row = mysqli_num_rows($last_view_result);

$afterhour = date('Y-m-d H:i:s', strtotime('+1 hours'));

if (($last_view_row === 0) || (strtotime($current_time) > strtotime($last_date))) {
    $connect->query("UPDATE note SET hit = hit + 1 WHERE number = $number");
    $connect->query("INSERT INTO noteren (userIP, boardID, date) VALUES ('$ip', $number, '$afterhour')");
} else {
 
}

if (isset($_SESSION['userID'])) {
    $user_id = $_SESSION['userID'];

    $conn = mysqli_connect("  ", "  ", "  ", "  ") or die("connect fail");

    $query = "SELECT roll FROM user WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userRoll);
    mysqli_stmt_fetch($stmt);

    $showModifyDelete = ($userRoll === 'admin');

    mysqli_stmt_close($stmt);

    mysqli_close($conn);
} else {
    $showModifyDelete = false;
}
?>



<table class="board_view_table" align="center">
<tr>
        <td colspan="4" class="board_view_title"><?php echo $rows['title'] ?></td>
    </tr>
    <tr>
        <td class="board_view_id">작성자</td>
        <td class="board_view_id2"><?php echo $rows['id'] ?></td>
        <td class="board_view_hit">조회수</td>
        <td class="board_view_hit2"><?php echo $rows['hit'] ?></td>
    </tr>
    <tr>
        <td colspan="4" class="board_view_content" valign="top">
            <?php echo $rows['content'] ?>
        </td>
    </tr>

    <td>
        <?php if (!empty($rows['filename'])) : ?>
            <p>첨부 파일: <a href="bdownload.php?filename=<?php echo urlencode($rows['filename']); ?>&size=<?php echo $rows['filesize']; ?>&type=<?php echo urlencode($rows['filetype']); ?>"><?php echo $rows['filename']; ?></a></p>
        <?php else: ?>
            파일 없음
        <?php endif; ?>
        </td>
    </table>

<!-- MODIFY & DELETE -->
<div class="board_view_btn">
    <button class="board_view_btn1" onclick="location.href='./board.php'">목록으로</button>
    <?php if ($showModifyDelete) : ?>
        <button class="board_view_btn1" onclick="location.href='./board_modify.php?number=<?php echo $number ?>'">수정</button>
        <button class="board_view_btn1" onclick="location.href='./board_delete.php?number=<?php echo $number ?>'">삭제</button>
    <?php endif; ?>
</div>
