<?php
  session_start();
  $showWriteLink = true; // 글쓰기 링크를 보일지 여부를 초기화
  
  $host = "localhost";
  $username = "  ";
  $password = "  ";
  $database = "  ";
  $connect = mysqli_connect('localhost', '  ', '  ', '  ') or die("connect fail");
  
  // 데이터베이스에서 게시물 목록을 가져옵니다.
  $query = "SELECT * FROM jilmun ORDER BY number DESC";
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
<?php if ($showWriteLink) ?>
    <h2 align="center">Q&A</h2>
    <tb></tb>
    <!-- 검색 옵션과 검색 버튼 -->
    <div style="display: flex; align-items: center; justify-content: center;">
            <form name="qbsearch" action="qbsearch.php" method="GET" style="display: inline;">
                <span>
                    <select name="search_option">
                        <option value="title">제목</option>
                        <option value="content">내용</option>
                        <option value="userID">작성자</option>
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
               
            </tr>
        </thead>
        <tbody>
            <?php
            while ($rows = mysqli_fetch_assoc($result)) { // DB에 저장된 데이터 수 (열 기준)
                if ($total % 2 == 0) {
                    echo '<tr class="even">';
                } else {
                    echo '<tr>';
                }
            ?>
                <td width="50" align="center"><?php echo $total ?></td>
                <td width="500" align="center">
                    <a href="qna_view.php?number=<?php echo $rows['number'] ?>">
                        <?php echo $rows['title'] ?>
                    </a>
                </td>
                <td width="100" align="center"><?php echo $rows['userID'] ?></td>
                <td width="200" align="center"><?php echo $rows['date'] ?></td>
                
                
            </tr>
            <?php
                $total--;
            }
            ?>
        </tbody>
    </table>

    <?php if ($showWriteLink) ?>
        <div class="text">
            <font style="cursor: hand" onClick="location.href='./qna_write.php'">글쓰기</font>
        </div>

</body>
</html>
