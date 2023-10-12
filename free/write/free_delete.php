<?php
// delete.php 파일에서 ID 값을 받아옴
if (isset($_GET['ID'])) {
    $ID = $_GET['ID'];
    include '../../config.php';
    $connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, "User") or die("connect fail");

    $query = $connect->prepare("DELETE FROM freeBoard WHERE ID= ?");
    $query->bind_param('i', $ID);
    $result = $query->execute();

    if ($result) {
        ?>
        <script>
            alert("삭제되었습니다.");
            location.replace("../free_index.php");
        </script>
        <?php
    } else {
        echo "fail";
    }
} else {
    echo "ID 값이 전달되지 않았습니다.";
}
?>