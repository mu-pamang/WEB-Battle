<?php
session_start();
$showWriteLink = false; 

if (isset($_SESSION['userID'])) {
    $session_userID = $_SESSION['userID'];

$conn = mysqli_connect("  ", "  ", "  ", "  ") or die("connect fail");

    $query = "SELECT roll FROM user WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $session_userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userRoll);
    mysqli_stmt_fetch($stmt);

    if ($userRoll === 'admin') {
        $showWriteLink = true;
    } else {
        $showWriteLink = false;
    }

    mysqli_stmt_close($stmt);

    mysqli_close($conn);
}
?>

<?php
if (isset($_GET['order'])) {
    $order = $_GET['order'];
    setcookie("board_order", $order, time() + (86400 * 30), "/");
} else {
    if (isset($_COOKIE['board_order'])) {
        $order = $_COOKIE['board_order'];
    } else {
        $order = "number";
    }
}

$connect = mysqli_connect("  ", "  ", "  ", "  ") or die("connect fail");
if ($order === "hit") {
    $query = "select * from note order by hit desc";
} else {
    $query = "select * from note order by number desc";
}
$result = $connect->query($query);
$total = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
</head>
<style>
        table{
                border-top: 1px solid #444444;
                border-collapse: collapse;
        }
        tr{
                border-bottom: 1px solid #444444;
                padding: 10px;
        }
        td{
                border-bottom: 1px solid #efefef;
                padding: 10px;
        }
        table .even{
                background: #efefef;
        }
        .text{
                text-align:center;
                padding-top:20px;
                color:#000000
        }
        .text:hover{
                text-decoration: underline;
        }
        a:link {color : #57A0EE; text-decoration:none;}
        a:hover { text-decoration : underline;}
</style>
<body>
    <h2 align="center">공지사항</h2>
    <div style="display: flex; align-items: center; justify-content: center;">
            <form name="bsearch" action="bsearch.php" method="GET" style="display: inline;">
                <span>
                    <select name="search_option">
                        <option value="title">제목</option>
                        <option value="content">내용</option>
                        <option value="id">작성자</option>
                    </select>
                    <input type="text" name="search_text" value="<?= isset($_POST['search_text']) ? $_POST['search_text'] : '' ?>">
                    <input type="submit" value="검색">
                </span>
            </form>
        </div>

    <table align="center">
        <thead align="center">
            <tr>
                <td width="50" align="center">번호</td>
                <td width="500" align="center">제목</td>
                <td width="100" align="center">작성자</td>
                <td width="200" align="center">날짜</td>
                <td width="50" align="center">조회수</td>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($rows = mysqli_fetch_assoc($result)) { 
                if ($total % 2 == 0) {
                    echo '<tr class="even">';
                } else {
                    echo '<tr>';
                }
            ?>
                <td width="50" align="center"><?php echo $total ?></td>
                <td width="500" align="center">
                    <a href="board_view.php?number=<?php echo $rows['number'] ?>">
                        <?php echo $rows['title'] ?>
                    </a>
                </td>
                <td width="100" align="center"><?php echo $rows['id'] ?></td>
                <td width="200" align="center"><?php echo $rows['date'] ?></td>
                <td width="50" align="center"><?php echo $rows['hit'] ?></td>
            </tr>
            <?php
                $total--;
            }
            ?>
        </tbody>
    </table>
    <?php if ($showWriteLink) : ?>
        <div class="text">
            <font style="cursor: hand" onClick="location.href='./board_write.php'">글쓰기</font>
        </div>
    <?php endif; ?>

    <div class="text">
        <a href="?order=number" style="margin-right: 10px;">순번순</a>
        <a href="?order=hit">조회순</a>
    </div>
</body>
</html>
