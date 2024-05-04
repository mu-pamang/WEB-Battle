<?php
if (isset($_GET['filename']) && isset($_GET['size']) && isset($_GET['type'])) {
    $file_name = $_GET['filename'];
    $file_size = $_GET['size'];
    $file_type = $_GET['type'];

    $upload_directory = '/uploads/'; 
    $file_path = $upload_directory . $file_name;

    header("Content-length: $file_size");
    header("Content-type: $file_type");
    header("Content-Disposition: attachment; filename=$file_name");

    readfile($file_path);
    exit;
} else {
    echo "파일을 찾을 수 없습니다.";
}
?>
