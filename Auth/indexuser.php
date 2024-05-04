<?php
                $connect = mysqli_connect('  ', '', '', '') or die ("connect fail");
                $query ="select * from board order by number desc";
                $result = $connect->query($query);
                $total = mysqli_num_rows($result);
 
                session_start();
 
                if(isset($_SESSION['userid'])) {
                        echo $_SESSION['userid'];?>님 안녕하세요
                        <br>
                } else{
                  <button onclick="location.href='./login.php'">로그인</button>
                  <br>
                }
?>
