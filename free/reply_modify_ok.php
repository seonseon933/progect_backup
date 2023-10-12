<?php
    include '../config.php';
    $connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("connect fail");

    $ID = $_POST['ID']; //댓글번호
    $sql = $connect->prepare("SELECT * FROM reply WHERE ID = ?");
    $sql->bind_param('i', $ID);
    $sql->execute();
    $result = $sql->get_result();
    $reply = $result->fetch_assoc();

    $board_num = $_POST['board_num']; //게시글 번호
    $sql2 = $connect->prepare("SELECT * FROM freeBoard WHERE ID = ?");
    $sql2->bind_param('i', $board_num);
    $sql2->execute();
    $result2 = $sql2->get_result();
    $board = $result2->fetch_assoc();

    $date = date('Y-m-d H:i:s');
    $sql3 = $connect->prepare("UPDATE reply SET content= ?, date= ? WHERE ID = ?");
    $sql3->bind_param('ssi', $_POST['content'],  $date, $ID);
    $result3 = $sql3->execute();

    if($result3){
    ?>
        <script>
            type="text/javascript">alert('수정되었습니다.'); 
            location.replace("free_view.php?ID=<?= $board_num?>");
        </script>
    <?php    
    }
    else {
        echo "fail";
        }
?>