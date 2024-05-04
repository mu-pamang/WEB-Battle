<?php
session_start();
$connect = mysqli_connect("  ", "  ", "  ", "  ") or die("connect fail");

if (isset($_GET['number'])) {
    $number = $_GET['number'];
    $query = "SELECT title, content, date, id FROM freeno WHERE number=$number";
    $result = $connect->query($query);
    $rows = mysqli_fetch_assoc($result);

    $title = $rows['title'];
    $content = $rows['content'];
    $usrid = $rows['id'];

  if (isset($_SESSION['userID'])) {
    $user_id = $_SESSION['userID'];

    $conn = mysqli_connect("  ", "  ", "  ", "  ") or die("connect fail");

    // userID를 이용하여 'user' 테이블에서 해당 사용자의 roll 값을 가져옴
    $query = "SELECT roll FROM user WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userRoll);
    mysqli_stmt_fetch($stmt);
    
    // 사용자가 'admin'이거나 자신의 글인 경우에만 수정 가능
    if ($userRoll === 'admin' || ($userRoll === 'user' && $user_id === $usrid)) {
        $allowModify = true;
    } else {
        $allowModify = false;
    }

    mysqli_stmt_close($stmt);

    mysqli_close($conn);
    } else {
        $allowModify = false;
    }

    $URL = "./fboard.php";
} else {

    ?>
    
    <form method="get" action="">
        <label>게시물 번호를 입력해주세요:</label>
        <input type="text" name="number">
        <input type="submit" value="확인">
    </form>
    <?php
    exit; 
?>

<?php if ($allowModify) : ?>
    <form method="post" action="fboard_modify_action.php">
        <table style="padding-top:50px" align="center" width=700 border=0 cellpadding=2>
            <tr>
                <td height=20 align=center bgcolor=#ccc><font color=white>글수정</font></td>
            </tr>
            <tr>
                <td bgcolor=white>
                    <table class="table2">
                        <tr>
                            <td>작성자</td>
                            <td><input type="hidden" name="id" value="<?= $usrid ?>"></td>
                        </tr>
                        <tr>
                            <td>제목</td>
                            <td><input type="text" name="title" size=60 value="<?= $title ?>"></td>
                        </tr>
                        <tr>
                            <td>내용</td>
                            <td><textarea name="content" cols=85 rows=15><?= $content ?></textarea></td>
                        </tr>
                    </table>
                    <center>
                        <input type="hidden" name="number" value="<?= $number ?>">
                        <input type="submit" value="작성">
                    </center>
                </td>
            </tr>
        </table>
    </form>
<?php else : ?>
    <p>권한이 없습니다. 자신의 글이 아니거나 관리자 권한이 필요합니다.</p>
<?php endif; ?>
