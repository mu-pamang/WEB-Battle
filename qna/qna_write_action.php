<?php
session_start();
$connect = mysqli_connect("  ", "  ", "  ", "  ") or die("fail");

$id = $_POST['name'];                                    
$title = $_POST['title'];                
$content = $_POST['content'];             
$date = date('Y-m-d H:i:s');           
$secret = (int)$_POST['secret'];

// 파일 업로드 처리
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
            $query = "INSERT INTO jilmun (title, content, date, userID, secret, filename, filesize, filetype)
                    VALUES ('$title', '$content', '$date', '$id', $secret,'$file_name', $file_size, '$file_type')";
            $result = $connect->query($query);
            if (!$result) {
                $URL = './fboard.php'; // return URL
?>
                <script>
                    alert("글이 등록되었습니다.");
                    location.replace("<?php echo $URL ?>");
                    exit();
                </script>
<?php
            }
        }
    } else {
        echo "파일 업로드 실패";
        exit;
    }
} else {

    $query = "INSERT INTO jilmun (title, content, date, userID, secret, filename, filesize, filetype) 
                        values('$title', '$content', '$date', '$id', '$secret', '0', '0', '0')";

    $result = $connect->query($query);
}
$URL = './qna.php'; //return URL
if ($result) {
?>
    <script>
        alert("<?php echo "글이 등록되었습니다." ?>");
        location.replace("<?php echo $URL ?>");
    </script>
<?php
} else {
    echo "FAIL";
}

mysqli_close($connect);
?>
