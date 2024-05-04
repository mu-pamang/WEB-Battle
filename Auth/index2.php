<?php
session_start();
// 로그아웃 요청이 있을 경우 세션 파기 (로그아웃)
if (isset($_GET['logout'])) {
    session_destroy(); // 세션 파기
    header("Location: index.php"); // index.php 페이지로 리디렉션
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/style_index.css">
    <script src="./js/script.js"></script>
    <title>
        Hye Jun's
    </title>
</head>
<body>
    <div class="super_container">
        <header>
            <div class="header_container">
                <div class="logo_container">
                    <a href="/index2.php">Hye Jun's Web</a>
                </div>
                <div class="nav_container" id="nav_menu">
                    <div class="menu_container">
                        <ul class="menu">
                            <li class="menu_1">
                                <a class="menu_title" ; href="/board.php">공지사항</a>
                                <ul class="menu_1_content">
                                    <li class="menu_title"><a class="menu_title" href="/board.php">공지사항</a></li>
                                </ul>
                            </li>

                            <li class="menu_2">
                                <a class="menu_title" ; href="/fboard.php">자유게시판</a>
                                <ul class="menu_2_content">
                                    <li><a class="menu_index" href="/fboard.php">메뉴 2 - 1</a></li>
                                    <li><a class="menu_index" href="#">메뉴 2 - 2</a></li>
                                    <li><a class="menu_index" href="#">메뉴 2 - 3</a></li>
                                </ul>
                            </li>
                            <li class="menu_3">
                                <a class="menu_title" ; href="/qna.php">Q&A</a>
                                <ul class="menu_3_content">
                                    <li><a class="menu_index" href="/qna.php">메뉴 3 - 1</a></li>
                                    <li><a class="menu_index" href="#">메뉴 3 - 2</a></li>
                                    <li><a class="menu_index" href="#">메뉴 3 - 3</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div class="logout_container">

                        <ul class="logout">
                            <br />
                        </ul>

                        <div class="logout_container">
                            <?php
                            // 로그인 상태인 경우에만 사용자 이름과 로그아웃 버튼 표시
                            if (isset($_SESSION['userID'])) {
                                echo '<div class="logout">';
                                echo '' . $_SESSION['userID'] . '님 안녕하세요 &nbsp;&nbsp;';
                                echo '<a style="font-size: 20px; text-decoration: none; color: rgba(0,0,0,1);" href="?logout">로그아웃</a>';
                                echo '</div>';
                            } else {
                                // 로그인 상태가 아닌 경우 로그인 버튼 표시
                                echo '<ul class="logout">';
                                echo '<li class="menu_login"><a class="menu_title" href="login.php">로그인</a></li>';
                                echo '</ul>';
                            }
                            ?>
                        </div>

                    </div>
                </div>
        <script>
            function logout() {
                // '로그아웃이 되었습니다.' 라는 팝업 창 띄우기
                alert('로그아웃이 되었습니다.');

                // 로그아웃 처리를 위해 session.php 파일로 리디렉션
                window.location.href = 'login_ok.php?logout';
            }
        </script>
    </header>

    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; flex-grow: 1;">
        <form name="allsearch" action="allsearch.php" method="GET" style="display: inline;">
            <span>
                <select name="search_option">
                    <option value="title">제목</option>
                    <option value="content">내용</option>
                    <option value="author">작성자</option>
                </select>
                <input type="text" name="search_text" value="<?= isset($_POST['search_text']) ? $_POST['search_text'] : '' ?>">
                <input type="submit" value="검색">
            </span>
        </form>
    </div>

    <footer>
    <div class="footer_container">
        <div class="footA">
        </div>
        <div class="footB">
            Copyright © 2023 All Rights Reserved.
        </div>
    </div>
    </footer>   
    </div>
    
</body>
</html>
