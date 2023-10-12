<!--free_index.php -->
<!--자유: | 번호 | 제목 | 내용 | 작성자 | 날짜 | 조회수 | 추천 | 파일
댓글: | 번호 | 자유번호 | 작성자 | 내용 | 날짜 | -->
<?php
    session_start();
    include '../config.php';
    if(isset($_SESSION['userID'])) {

    }else{
    echo "<script>alert('로그인이 필요합니다.'); window.location.href='../login/login.php';</script>";
    }
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
    <button class="w-btn w-btn-gray" onclick="location.href='./QnA/qna_index.php'">QnA</button>

    <script>
        function goBack() {
            // 세션 확인
            var isAdmin = <?php echo isset($_SESSION['userID']) && ($_SESSION['userID'] == 'Admin') ? 'true' : 'false'; ?>;
            if (isAdmin) {
                window.location.href = '../admin_ok/admin_index.php'; // admin이면 admin_index.php로 이동
            } else {
                window.location.href = '../user_ok/user_index.php'; // 세션이 있으나 admin이 아니라면 user_index.php로 이동
            }
        }
    </script>
</div>

<?php
$connect = mysqli_connect($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("connect fail");

// 정렬 기준 선택 처리  + 추천순 추가 예정
$order = isset($_GET['order']) && ($_GET['order'] === 'hit' || $_GET['order'] === 'thumb') ? $_GET['order'] : 'ID';
$query = "SELECT * FROM freeBoard ORDER BY $order DESC";
$result = mysqli_query($connect, $query);
$total = mysqli_num_rows($result);
?>

<h2 align=center>자유게시판</h2>
<div align=center>
    <div style="padding-bottom: 10px;">
        <form method="get">
            <label for="order">정렬 기준:</label> <!-- 추천순 추가 예정 -->
            <select name="order" id="order" onchange="this.form.submit()">
                <option value="ID" <?php echo $order === 'ID' ? 'selected' : ''; ?>>순번순</option>
                <option value="hit" <?php echo $order === 'hit' ? 'selected' : ''; ?>>조회수순</option>
                <option value="thumb" <?php echo $order === 'thumb' ? 'selected' : ''; ?>>추천수순</option>
            </select>
        </form>
    </div>
    <table align=center>
        <thead align="center">
        <tr>
            <td width="50" align="center">번호</td>
            <td width="500" align="center">제목</td>
            <td width="100" align="center">작성자</td>
            <td width="200" align="center">날짜</td>
            <td width="50" align="center">조회수</td>
            <td width="50" align="center">추천수</td>
        </tr>
        </thead>

        <tbody>
        <?php  // 정렬
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
            <td width="50" align="center"><?php echo $row['ID'] ?></td>
            <td width="500" align="center">
                <a href="free_view.php?ID=<?php echo $row['ID'] ?>">
                    <?php echo $row['title'] ?></a></td>
            <td width="100" align="center"><?php echo $row['userID'] ?></td>
            <td width="200" align="center"><?php echo $row['date'] ?></td>
            <td width="50" align="center"><?php echo $row['hit'] ?></td>
            <td width="50" align="center"><?php echo $row['thumb'] ?></td>
            </tr>
            <?php
            $total--; // total 필요없어지면 지우기.
        }
        ?>
        </tbody>
    </table>
        <div class="text">
            <button class="w-btn w-btn-gray" style="cursor: hand" onclick="location.href='write/free_write.php'">글쓰기</button>
        </div>
</div>
</body>
</html>