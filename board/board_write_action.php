<?php
session_start();

$connect = mysqli_connect("  ", "  ", "  ", "  ") or die("fail");

$id = $_POST['name'];                                      
$title = $_POST['title'];              
$content = $_POST['content'];        
$date = date('Y-m-d H:i:s');        

if (($_FILES['file_upload']['name']) != '') {
        if ($_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
                $file_name = $_FILES['file_upload']['name'];
                $file_size = $_FILES['file_upload']['size'];
                $tmp_name = $_FILES['file_upload']['tmp_name']; 
                $file_type = $_FILES['file_upload']['type'];

                $upload_directory = '/uploads/'; 
                $upload_file_path = $upload_directory . $file_name;

                if (!is_dir($upload_directory)) {
                        @mkdir($upload_directory, 0777, true);
                }

                if (move_uploaded_file($tmp_name, $upload_file_path)) {
                        $query = "INSERT INTO note (title, content, date, hit, id, password, filename, filesize, filetype)
                        VALUES ('$title', '$content', '$date', 0, '$id', 0, '$file_name', $file_size, '$file_type')";
                        $result = $connect->query($query);
                        if ($result) {


                                $URL = './board.php'; 
                ?>
                                <script>
                                    alert("글이 등록되었습니다.");
                                    location.replace("<?php echo $URL ?>");
                                    exit();
                                </script>
                
                        <?php
                            } 
                } else {
                        echo "파일 업로드 실패";
                        exit;
                }
        }
} else {

        $query = "INSERT INTO note (title, content, date, hit, id, password, filename, filesize, filetype) 
                        values('$title', '$content', '$date',0, '$id', '','',0,'')";


        $result = $connect->query($query);
}
$URL = './board.php';             
if ($result) {

?> <script>
                alert("<?php echo "글이 등록되었습니다." ?>");
                location.replace("<?php echo $URL ?>");
   </script>

<?php
} else {
        echo "FAIL";
}

mysqli_close($connect);
?>
