<?php
    include '../../config.php';
    $connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, "User") or die ("connect fail");
    $ID = $_POST['ID'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = date('Y-m-d H:i:s');

    $query = $connect->prepare("UPDATE noticeBoard SET title = ?, content = ?, date = ? WHERE ID = ?");
    $query->bind_param('sssi', $title, $content, $date, $ID);

    $result = $query->execute();
    if($result) {
?>
        <script>
            alert("수정되었습니다.");
            location.replace("../notice_view.php?ID=<?=$ID?>");
        </script>
<?php    }
    else {
        echo "fail";
    }
?>
