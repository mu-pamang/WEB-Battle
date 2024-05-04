<?php
    include 'conn.php';
    $conn = new mysqli("  ", "  ", "  ", "  ");
    session_start();
    if(isset($_GET['id']) && isset($_SESSION['id'])){
        //[id를 이용하여 username과 file_name 가져오는 코드];
        $sql_file = "SELECT * from upload where userID = ? and file_name = ?";
        $pre_state_file = $conn->prepare($sql_file);
        $pre_state_file->bind_param("ss", $user, $file_name);
        $pre_state_file->execute();

        $result = $pre_state_file->get_result();
        if($row = $result->fetch_assoc()){
            $type = $row['type'];
            $image = $row['file'];
            echo '<img src="data:'.$type.';base64,'.base64_encode($image).'"/>';
        }else{
            echo "<script>alert('존재하지않는 파일입니다.');</script>";
            echo "<script>window.location.href='read.php?id=$id';</script>";
        }
    }
?>
