<?php
    include '../../config.php';
    if (isset($_GET['ID'])) {
        $ID = $_GET['ID'];

        $connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, "User") or die("connect fail");

        $query = $connect->prepare("DELETE FROM Q_reply WHERE ID= ?");
        $query->bind_param('i', $ID);
        $query->execute();
        
        if ($query->execute()) {
            ?>
            <script>
                alert("삭제되었습니다.");
                location.replace("qna_view.php?ID=<?=$ID?>");
            </script>
            <?php
        } else {
            echo "fail";
        }
    } else {
        echo "ID 값이 전달되지 않았습니다.";
    }
?>