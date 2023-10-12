<!--notice_index.php -->
<?php
    session_start();
    include '../config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>HWEB</title>
    <link rel="stylesheet" type="text/css" href="../button.css">
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
        }x
    </script>
</div>

<?php
$connect = mysqli_connect($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("connect fail");

// 정렬 기준 선택 처리
$order = isset($_GET['order']) && $_GET['order'] === 'hit' ? 'hit' : 'ID';
$query = "SELECT * FROM noticeBoard ORDER BY $order DESC";
$result = mysqli_query($connect, $query);
$total = mysqli_num_rows($result);
?>

<h2 align=center>공지사항</h2>
<div align=center>
    <div style="padding-bottom: 10px;">
        <form method="get">
            <label for="order">정렬 기준:</label>
            <select name="order" id="order" onchange="this.form.submit()">
                <option value="ID" <?php echo $order === 'ID' ? 'selected' : ''; ?>>순번순</option>
                <option value="hit" <?php echo $order === 'hit' ? 'selected' : ''; ?>>조회수순</option>
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
        </tr>
        </thead>

        <tbody>
        <?php
        $rows_array = array();
        while ($rows = mysqli_fetch_assoc($result)) {
            $rows_array[] = $rows;
        }

        if ($order === 'hit') {
            usort($rows_array, function ($a, $b) {
                return $b['hit'] - $a['hit'];
            });
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
                <a href="notice_view.php?ID=<?php echo $row['ID'] ?>">
                    <?php echo $row['title'] ?></a></td>
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
    if (isset($_SESSION['userID']) && $_SESSION['userID'] === 'Admin') {
        ?>
        <div class="text">
            <button class="w-btn w-btn-gray" style="cursor: hand" onclick="location.href='write/notice_write.php'">글쓰기</button>
        </div>
    <?php
    }
    ?>
</div>
</body>
</html>