<?php
$connect = mysqli_connect("  ", "  ", "  ", "  ") or die("connect fail");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $number = $_POST['number'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $query = "UPDATE freeno SET title='$title', content='$content' WHERE number=$number";
    $result = mysqli_query($connect, $query);

    if ($result) {
        ?>
        <script>
            alert("수정되었습니다.");
            location.replace("./fboard_view.php?number=<?= $number ?>");
        </script>
        <?php
    } else {
        echo "fail";
    }
} else {
    echo "잘못된 접근입니다.";
}
?>
