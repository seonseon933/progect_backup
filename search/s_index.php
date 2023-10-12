<?php
    // 검색 게시판
    session_start();
    include '../config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../button.css">
    <title>HWEB</title>
    <meta charset="utf-8">
</head>
<style>
    table {
        border-top: 1px solid #444444;
        border-collapse: collapse;
        width: 90%;
    }

    tr {
        border-bottom: 1px solid #444444;
        padding: 10px;
    }

    td {
        border-bottom: 1px solid #efefef;
        padding: 10px;
    }

    table .even {
        background: #efefef;
    }

    .text {
        text-align: center;
        padding-top: 20px;
        color: #000000;
    }

    .text:hover {
        text-decoration: underline;
    }

    a:link {
        color: #57A0EE;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
<body>
<div>
    <button class="w-btn w-btn-gray" onclick="goBack()">메인화면</button>

    <script>
        function goBack() {
            // 세션 확인
            var isAdmin = <?php echo (($_SESSION['userID']) == 'Admin') ? 'true' : 'false'; ?>;
            var isLoggedIn = <?php echo isset($_SESSION['userID']) ? 'true' : 'false'; ?>;

            if (isAdmin) {
                window.location.href = '../admin_ok/admin_index.php'; // admin이면 admin_index.php로 이동
            } else if (isLoggedIn) {
                window.location.href = '../user_ok/user_index.php'; // 세션이 있으나 admin이 아니라면 user_index.php로 이동
            } else {
                window.location.href = '../index.php'; // 세션이 없으면 ./index.php로 이동
            }
        }
    </script>
</div>

<?php
$connect = mysqli_connect($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("connect fail");

$board = $_GET['board'];
$category = $_GET['catgo'];
$search_con = $_GET['search'];
?>

<h2 align=center>검색 게시판</h2>
<div align=center>
    <div style="padding-bottom: 10px;">
        <form action="s_index.php" method="get">
            <select name="board">
                <option value="all">전체검색</option>
                <option value="notice">공지게시판</option>
                <option value="free">자유게시판</option>
                <option value="QnA">질문게시판</option>
            </select>  
        <select name="catgo">
            <option value="title">제목</option>
            <option value="userID">작성자</option>
            <option value="content">내용</option>
        </select>
        <input type="text" name="search" size="40" required="required"/> <button class="w-btn w-btn-gray">검색</button>
        </form>
    </div>
    <table align=center>
        <thead align="center">
        <tr>
            <td width="50" align="center">번호</td>
            <td width="500" align="center">게시판</td>
            <td width="500" align="center">제목</td>
            <td width="100" align="center">작성자</td>
            <td width="200" align="center">날짜</td>
            <td width="50" align="center">조회수</td>
        </tr>
        </thead>

        <?php
          if(($board == 'all')){
            $sql = 
            "(SELECT 'noticeBoard' as tableName, ID, userID, title, content, date, hit 
            FROM noticeBoard 
            WHERE $category like '%$search_con%' 
            ORDER BY ID DESC) 
            UNION 
            (SELECT 'freeBoard' as tableName, ID, userID, title, content, date, hit 
            FROM freeBoard 
            WHERE $category like '%$search_con%' 
            ORDER BY ID DESC) 
            UNION 
            (SELECT 'QnA' as tableName, ID, userID, title, content, date, hit 
            FROM QnA 
            WHERE $category like '%$search_con%' AND lock_post=0
            ORDER BY ID DESC)";

            $result = mysqli_query($connect, $sql);
            while($row = mysqli_fetch_assoc($result)){
              echo "<tr>";
              echo "<td align='center'>".$row['ID']."</td>";
              switch($row['tableName']) {
                  case 'noticeBoard':
                      echo "<td align='center'>공지게시판</td>";
                      break;
                  case 'freeBoard':
                      echo "<td align='center'>자유게시판</td>";
                      break;
                  case 'QnA':
                      echo "<td align='center'>질문게시판</td>";
                      break;
              }
              switch($row['tableName']) {
                  case 'noticeBoard':
                      echo "<td align='center'><a href='../notice/notice_view.php?ID=".$row['ID']."'>".$row['title']."</a></td>";
                      break;
                  case 'freeBoard':
                      echo "<td align='center'><a href='../free/free_view.php?ID=".$row['ID']."'>".$row['title']."</a></td>";
                      break;
                  case 'QnA':
                      echo "<td align='center'><a href='../free/QnA/qna_view.php?ID=".$row['ID']."'>".$row['title']."</a></td>";
                      break;
              }
              echo "<td align='center'>".$row['userID']."</td>";
              echo "<td align='center'>".$row['date']."</td>";
              echo "<td align='center'>".$row['hit']."</td>";
              echo "</tr>";
          }
        }  
        else if(($board == 'notice')){
            $sql2 = "SELECT * FROM noticeBoard WHERE $category LIKE '%$search_con%' ORDER BY ID DESC";
            $result = mysqli_query($connect, $sql2);
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td align='center'>".$row['ID']."</td>";
                echo "<td align='center'>공지게시판</td>";
                echo "<td align='center'><a href='../notice/notice_view.php?ID=".$row['ID']."'>".$row['title']."</a></td>";
                echo "<td align='center'>".$row['userID']."</td>";
                echo "<td align='center'>".$row['date']."</td>";
                echo "<td align='center'>".$row['hit']."</td>";
                echo "</tr>";
            }
        }
        else if(($board == 'free')){
            $sql3 = "SELECT * FROM freeBoard WHERE $category LIKE '%$search_con%' ORDER BY ID DESC";
            $result = mysqli_query($connect, $sql3);
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td align='center'>".$row['ID']."</td>";
                echo "<td align='center'>자유게시판</td>";
                echo "<td align='center'><a href='../free/free_view.php?ID=".$row['ID']."'>".$row['title']."</a></td>";
                echo "<td align='center'>".$row['userID']."</td>";
                echo "<td align='center'>".$row['date']."</td>";
                echo "<td align='center'>".$row['hit']."</td>";
                echo "</tr>";
            }
        }
        else if(($board == 'QnA')){
            $sql4 = "SELECT * FROM QnA WHERE $category LIKE '%$search_con%' AND lock_post='0' ORDER BY ID DESC";
            $result = mysqli_query($connect, $sql4);
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td align='center'>".$row['ID']."</td>";
                echo "<td align='center'>질문게시판</td>";
                echo "<td align='center'><a href='../free/QnA/qna_view.php?ID=".$row['ID']."'>".$row['title']."</a></td>";
                echo "<td align='center'>".$row['userID']."</td>";
                echo "<td align='center'>".$row['date']."</td>";
                echo "<td align='center'>".$row['hit']."</td>";
                echo "</tr>";
            }
        }

        ?>
    </table>
</div>
</body>
</html>