<?php
    $connect = mysqli_connect("  ", "  ", "  ", "  ") or die("connect fail");

    if (isset($_GET['number'])) {
        $number = $_GET['number'];

        $query = "DELETE FROM note WHERE number=$number";
        $result = mysqli_query($connect, $query);

        if ($result) {
            ?>
            <script>
                alert("게시물이 삭제되었습니다.");
                location.replace("./board.php");
            </script>
            <?php
        } else {
            echo "게시물 삭제에 실패했습니다.";
        }
    } else {
        echo "잘못된 접근입니다.";
    }
?>
