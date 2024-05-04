<?php
session_start();
if (isset($_SESSION['userID'])) {
    $writer = $_SESSION['userID'];
} else {
    // 로그인하지 않은 경우 빈 값을 사용자로 지정
    $writer = "";
}

$connect = mysqli_connect("  ", "  ", "  ", "  ") or die("fail");

// POST로 전송된 데이터를 가져옴
$id = $_POST['name'];         
$title = $_POST['title'];      
$content = $_POST['content'];  
$date = date('Y-m-d H:i:s');   


if (($_FILES['file_upload']['name']) != '') {


    if ($_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['file_upload']['name'];
        $file_size = $_FILES['file_upload']['size'];
        $tmp_name = $_FILES['file_upload']['tmp_name']; //임시 파일 경로
        $file_type = $_FILES['file_upload']['type'];

        // 파일을 저장할 경로와 파일명 설정
        $upload_directory = '/uploads/'; // 업로드된 파일을 저장할 디렉토리
        $upload_file_path = $upload_directory . $file_name;

        // 업로드된 파일을 저장할 디렉토리가 존재하지 않는 경우 생성
        if (!is_dir($upload_directory)) {


            @mkdir($upload_directory, 0777, true);
        }

        // move_uploaded_file 함수를 사용하여 파일을 업로드 디렉토리로 이동
        if (move_uploaded_file($tmp_name, $upload_file_path)) {


            // 파일 업로드 성공 시 파일 정보를 DB에 추가
            $query = "INSERT INTO freeno (title, content, date, hit, id, password, filename, filesize, filetype)
                    VALUES ('$title', '$content', '$date', 0, '$id', 0, '$file_name', $file_size, '$file_type')";
            $result = $connect->query($query);
            if ($result) {


                $URL = './fboard.php'; // return URL  
?>
                <script>
                    alert("글이 등록되었습니다.");
                    location.replace("<?php echo $URL ?>");
                    exit();
                </script>

        <?php
            } else {
                echo "FAIL";
            }
        } else {
            echo "파일 업로드 실패";
            exit;
        }
    }
} else {
    // 기존 코드
    $query = "INSERT INTO freeno (title, content, date, hit, id, password,filename,filesize,filetype)
                    VALUES ('$title', '$content', '$date', 0, '$id', 0, '', 0, '')";

    $result = $connect->query($query);
    $URL = './fboard.php'; // return URL                   
    if ($result) {

        ?>
        <script>
            alert("글이 등록되었습니다.");
            location.replace("<?php echo $URL ?>");
            exit();
        </script>

<?php
    } else {
        echo "FAIL";
    }
}
mysqli_close($connect);
?>
