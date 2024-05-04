<?php
    include 'conn.php';
    $conn = new mysqli("  ", "  ", "  ", "  ");
    session_start();
    if($_FILES['upload_file'] != NULL){
        $userID = $_SESSION['id'];
        $tmp_name = $_FILES['upload_file']['tmp_name'];
        $size = getimagesize($_FILES['upload_file']['tmp_name']);
        $type = $size['mime'];
        $img = file_get_contents($_FILES['upload_file']['tmp_name']);
        
        $file_size = $_FILES['upload_file']['size'];
        $maxsize = 2000000;
        $name = $_FILES['upload_file']['name'];

        if($file_size < $maxsize){
            $sql = "INSERT INTO upload (userID, file_name, file, size, type) VALUES ('$user_id', '$file_name','$file ,$size ,'$type')";
            $pre_state = $conn->prepare($sql);
            $pre_state->bind_param("sssss", $userID, $name, $img, $file_size, $type);
            $pre_state->execute();

            if($result = $pre_state->get_result()){
                echo "<script>success</script>";
                echo "<script>window.location.href='fboard.php';</script>";
            }else{
                echo "<script>fail</script>";
            }
        }
        else{
            echo "<script>alert('파일 사이즈가 너무 큽니다.')</script>";
        }
    }
?>
