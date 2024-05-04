<?php
    $connect = mysqli_connect("  ", "  ", "  ", "  ") or die("connect fail");

    if (isset($_GET['number'])) {
        $number = $_GET['number'];

        // 게시물 삭제 쿼리 실행
        $query = "DELETE FROM jilmun WHERE number=$number";
        $result = mysqli_query($connect, $query);

        if ($result) {
            ?>
            <script>
                alert("글이 삭제되었습니다.");
                location.replace("./qna.php");
            </script>
            <?php
        } else {
            echo "글 삭제에 실패했습니다.";
        }
    } else {
        echo "잘못된 접근입니다.";
    }
?>
