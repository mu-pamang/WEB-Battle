<?php
    include 'conn.php';
    $conn = new mysqli("  ", "  ", "  ", "  ");
    session_start();
    if(isset($_GET['id']) && isset($_SESSION['id'])){
        $sql = "SELECT * FROM fboard where id = ?";

        $pre_state = $conn->prepare($sql);
        $pre_state->bind_param("s", $id);

        $id = $_GET['id'];
        $pre_state->execute();
        $result = $pre_state->get_result();

        $row = $result->fetch_assoc();
        $username = $row['userID'];
        $file_name = $row['file'];
        $path = "./files/$username";
        $file = "$path/$file_name";

        if (is_file($file)) {
            header("Content-type: application/octet-stream"); 
            header("Content-Length: ".filesize("$file"));
            header("Content-Disposition: attachment; filename=$file_name");
            header("Content-Transfer-Encoding: binary");
            header("Pragma: public"); 
            header("Expires: 0"); 
        
            $fp = fopen($file, "rb"); 
            fpassthru($fp);
            fclose($fp);
        }
        else {
            echo "해당 파일이 없습니다.";
        }
    }else{
        echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    }
    mysqli_close($conn);
?>
