<?php
session_start();
$host = "  ";
$username = "  ";
$password = "  ";
$database1 = "  "; 
$database2 = "  ";
$database3 = "  "; 

$conn1 = new mysqli("  ", "  ", "  ", "  ");
$conn2 = new mysqli("  ", "  ", "  ", "  ");
$conn3 = new mysqli("  ", "  ", "  ", "  ");

if ($conn1->connect_error || $conn2->connect_error || $conn3->connect_error) {
    die("데이터베이스 연결 실패: " . $conn1->connect_error . $conn2->connect_error . $conn3->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $search_option = $_GET["search_option"];
    $search_text = $_GET["search_text"];

    $sql1_condition = $search_option == 'author' ? 'id' : $search_option;
    $sql1 = "SELECT 'board' AS type, id AS author, title, content, date, number FROM note WHERE $sql1_condition LIKE '%$search_text%'";

    $sql2_condition = $search_option == 'author' ? 'id' : $search_option;
    $sql2 = "SELECT 'free' AS type, id AS author, title, content, date, number FROM freeno WHERE  $sql2_condition LIKE '%$search_text%'";

    $sql3_condition = $search_option == 'author' ? 'userID' : $search_option;
    $sql3 = "SELECT 'ques' AS type, userID AS author, title, content, date, number FROM jilmun WHERE  $sql3_condition LIKE '%$search_text%' AND secret = 0";

    $result1 = $conn1->query($sql1);
    $result2 = $conn2->query($sql2);
    $result3 = $conn3->query($sql3);

    $searchResult = array();
    
    while ($row = $result1->fetch_assoc()) {
        $searchResult[] = $row;
    }

    while ($row = $result2->fetch_assoc()) {
        $searchResult[] = $row;
    }

    while ($row = $result3->fetch_assoc()) {
        $searchResult[] = $row;
    }
    
    usort($searchResult, function ($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
} else {
    header("Location: index2.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset='utf-8'>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .main_container {
            max-width: 800px;
            margin: 20px auto;
            padding: 10px;
        }

        .search_result {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }

        .search_result h3 {
            margin: 0;
            padding: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .search_result h4 {
            margin-top: 10px;
            font-size: 16px;
        }

        .search_result p {
            margin: 5px 0;
            font-size: 14px;
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        footer a {
            color: #fff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="main_container">
    <?php if (!empty($searchResult)): ?>
        <?php foreach ($searchResult as $result): ?>
            <div class="search_result">
                <?php if ($result['type'] === 'board'): ?>
                    <h3>공지사항</h3>
                <?php elseif ($result['type'] === 'free'): ?>
                    <h3>자유게시판</h3>
                <?php elseif ($result['type'] === 'ques'): ?>
                    <h3>Q&A</h3>
                <?php endif; ?>
                <h4>제목: <a href="<?php echo $result['type'] === 'board' ? 'board_view.php?number=' : ($result['type'] === 'free' ? 'fboard_view.php?number=' : 'qna_view.php?number='); ?><?php echo $result['number']; ?>"><?php echo $result['title']; ?></a></h4>
              
                <p>내용: <?= $result['content'] ?></p>
                <p>작성자: <?= $result['author'] ?></p>
                <p>작성일: <?= $result['date'] ?></p>
            </div>
        
</div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="search_result">
            <p>검색 결과가 없습니다.</p>
        </div>
    <?php endif; ?>
</div>


    <footer>
        <a href="index2.php">돌아가기</a>
    </footer>
</body>
</html>
