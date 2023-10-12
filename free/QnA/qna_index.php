<!--notice_index.php -->
<?php
    session_start();
    include '../../config.php';
    if(isset($_SESSION['userID'])) {

    }else{
    echo "<script>alert('로그인이 필요합니다.'); window.location.href='../../login/login.php';</script>";
    }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../../button.css">
    <title>HWEB</title>
    <meta charset="utf-8">
</head>
<style>
    table {
        border-top: 1px solid #444444;
        border-collapse: collapse;
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
    <button class="w-btn w-btn-gray" onclick="goBack()">메인화면</button> <!-- 도르마무 -->
    <script>
        function goBack() {
            // 세션 확인
            var isAdmin = <?php echo (($_SESSION['userID']) == 'Admin') ? 'true' : 'false'; ?>;
            if (isAdmin) {
                window.location.href = '../../admin_ok/admin_index.php'; 
            } else {
                window.location.href = '../../user_ok/user_index.php'; 
            }
        }
    </script>
</div>

<?php
$connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("connect fail");
$query = "SELECT * FROM QnA ORDER BY ID DESC";
$result = mysqli_query($connect, $query);
$total = mysqli_num_rows($result);
?>

<h2 align=center>질문게시판</h2>
<div align=center>
    <table align=center>
        <thead align="center">
        <tr>
            <td width="50" align="center">번호</td>
            <td width="500" align="center">제목</td>
            <td width="100" align="center">작성자</td>
            <td width="200" align="center">날짜</td>
            <td width="50" align="center">조회수</td>
        </tr>
        </thead>

        <tbody>
        <?php
        $locking = "<img src='../../BBS/img/lock.png' alt='lock' title='lock' with='15' height='15' />";   // img 태그로 img 불러옴.
        $rows_array = array();
        while ($rows = mysqli_fetch_assoc($result)) {
            $rows_array[] = $rows;
        }

        foreach ($rows_array as $row) {
            if ($total % 2 == 0) {
                echo '<tr class="even">';
            } else {
                echo '<tr>';
            }
            ?>
            <td width="50" align="center"><?php echo $total ?></td>
            <td width="500" align="center">
            <?php
            if(($row['lock_post'] == '1') && ($_SESSION['userID'] == 'Admin')){ ?> 
              <a href="./qna_view.php?ID=<?php echo $row['ID']?>"><?php echo $row['title'], $locking?></a></td> <?php 
            }
            else if($row['lock_post'] == '1'){ ?>
                <a href="./ck_qna_view.php?ID=<?php echo $row['ID']?>"><?php echo $row['title'], $locking?></a></td>  <?php  // img 사용
            }else if($row['lock_post'] == '0'){  ?>
                <a href="./qna_view.php?ID=<?php echo $row['ID']?>"><?php echo $row['title']?></a></td> <?php
            } ?>    
            <td width="100" align="center"><?php echo $row['userID'] ?></td>
            <td width="200" align="center"><?php echo $row['date'] ?></td>
            <td width="50" align="center"><?php echo $row['hit'] ?></td>
            </tr>
            <?php
            $total--;
        }
        ?>
        </tbody>
    </table>
    <?php
    if ((isset($_SESSION['userID'])) && ($_SESSION['userID'] != 'Admin')) {
        ?>
        <div class="text">
            <button class="w-btn w-btn-gray" style="cursor: hand" onclick="location.href='write/qna_write.php'">글쓰기</button>
        </div>
    <?php
    }
    ?>
</div>
</body>
</html>
