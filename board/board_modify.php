<?php
$connect = mysqli_connect("  ", "  ", "  ", "  ") or die("connect fail");

if (isset($_GET['number'])) {
    $number = $_GET['number'];
    $query = "SELECT title, content, date, id FROM note WHERE number=$number";
    $result = $connect->query($query);
    $rows = mysqli_fetch_assoc($result);

    $title = $rows['title'];
    $content = $rows['content'];
    $usrid = $rows['id'];

    $URL = "./board.php";
} else {
    ?>
    <form method="get" action="">
        <label>게시물 번호를 입력해주세요:</label>
        <input type="text" name="number">
        <input type="submit" value="확인">
    </form>
    <?php
    exit; 
}
?>

<form method="post" action="board_modify_action.php">
    
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
