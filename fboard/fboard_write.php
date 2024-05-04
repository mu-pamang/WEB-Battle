<?php
session_start();
if (isset($_SESSION['userID'])) {
    $writer = $_SESSION['userID'];
} else {
    // 로그인하지 않은 경우 빈 값을 사용자로 지정
    $writer = "";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<style>
    table.table2 {
        border-collapse: separate;
        border-spacing: 1px;
        text-align: left;
        line-height: 1.5;
        border-top: 1px solid #ccc;
        margin: 20px 10px;
    }
    table.table2 tr {
        width: 50px;
        padding: 10px;
        font-weight: bold;
        vertical-align: top;
        border-bottom: 1px solid #ccc;
    }
    table.table2 td {
        width: 100px;
        padding: 10px;
        vertical-align: top;
        border-bottom: 1px solid #ccc;
    }
</style>
<body>  
    <form method="POST" action="/fboard_write_action.php" enctype="multipart/form-data">
        <table style="padding-top:50px" align="center" width=700 border=0 cellpadding=2>
            <tr>
                <td height=20 align=center bgcolor=#ccc><font color=white>글쓰기</font></td>
            </tr>
            <tr>
                <td bgcolor=white>
                    <table class="table2">
                        <tr>
                            <td>작성자</td>
                            <td>
                                <?php if ($writer !== "") : ?>
                                    <!-- 로그인한 사용자인 경우, 작성자를 고정하여 표시 -->
                                    <input type="text" name="name" size=20 value="<?php echo $writer; ?>" readonly>
                                <?php else : ?>
                                    <!-- 로그인하지 않은 경우, 사용자가 직접 작성자를 입력 -->
                                    <input type="text" name="name" size=20>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>제목</td>
                            <td><input type="text" name="title" size=60></td>
                        </tr>
                        <tr>
                            <td>내용</td>
                            <td><textarea name="content" cols=85 rows=15></textarea></td>
                        </tr>
                        
                        <tr>
                            <td>파일 업로드</td>
                            <td><input type="file" name="file_upload"></td>
                        </tr>
                    </table>
                    <center>
                        <input type="submit" value="작성">
                    </center>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
