<?php
    include 'conn.php';
    $conn = new mysqli("  ", "  ", "  ", "  ");
    session_start();
    if(isset($_GET['id']) && isset($_SESSION['id'])){
    //[id를 이용하여 username과 file_name 가져오는 코드];
        $sql_file = "SELECT * from upload where userID = ? and file_name = ?";
        $pre_state_file = $conn->prepare($sql_file);
        $pre_state_file->bind_param("ss", $userID, $file_name);
        $pre_state_file->execute();

        $result = $pre_state_file->get_result();
        if($row = $result->fetch_assoc()){
            $type = $row['type'];
            $size = $row['size'];
            $name = $row['file_name'];
            $image = $row['file'];
            header("Content-type: $type"); 
            header("Content-Length: $size");
            header("Content-Disposition: attachment; filename=$name");
            header("Content-Transfer-Encoding: binary");
            ob_clean();
            flush();

            echo $image;
        }
        else {
            echo "<script>alert('해당 파일이 없습니다.'); history.back();</script>";
        }
    }else{
        echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    }
    mysqli_close($conn);
?>
