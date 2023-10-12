<?php
    include '../../../config.php';
    $connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, "User") or die ("connect fail");
    $ID = $_GET['ID'];
    $title = $_GET['title'];
    $content = $_GET['content'];
    $date = date('Y-m-d H:i:s');
    $query = $connect->prepare("UPDATE QnA SET title= ?, content= ?, date = ? WHERE ID= ?");
    $query->bind_param('sssi',$title, $content, $date, $ID);

    if($query->execute()) {
?>
        <script>
            alert("수정되었습니다.");
            location.replace("../qna_view.php?ID=<?=$ID?>");
        </script>
<?php    }
    else {
        echo "fail";
    }
?>
