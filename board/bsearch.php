<?php
session_start();
   $host = "  ";
   $username = "  ";
   $password = "  ";
   $database = "  ";


$conn = new mysqli('localhost', 'root', '_pd/_9cp29dQ', 'board');

if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $search_option = $_GET["search_option"];
    $search_text = $_GET["search_text"];

    $sql = "SELECT 'board' AS type, id AS author, title, content, date, number FROM note WHERE $search_option LIKE '%$search_text%'";
   
    $result = $conn->query($sql);

    $searchResult = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResult[] = $row;
        }
    }
}

$conn->close();
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
                <?php endif; ?>
                <h4>제목: <a href="board_view.php?number=<?= $result['number']; ?>"><?php echo $result['title']; ?></a></h4>
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
        <a href="board.php">돌아가기</a>
    </footer>
</body>
</html>
