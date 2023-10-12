<?php
    include '../../config.php';
    $connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("connect fail");

    $ID = $_POST['ID']; //댓글번호
    $sql = $connect->prepare("SELECT * FROM Q_reply WHERE ID= ?"); // Q_reply
    $sql->bind_param('i', $ID);
    $sql->execute();
    $result = $sql->get_result();
    $reply = $result->fetch_assoc();

    $Q_num = $_POST['Q_num']; //게시글 번호
    $sql2 = $connect->prepare("SELECT * FROM QnA WHERE ID= ?"); //freeBoard
    $sql2->bind_param('i', $Q_num);
    $sql2->execute();
    $result2 = $sql2->get_result();
    $board = $result2->fetch_assoc();

    $date = date('Y-m-d H:i:s');

    $sql3 = $connect->prepare("UPDATE Q_reply SET content= ?, date= ? WHERE ID = ?");
    $sql3->bind_param('ssi', $_POST['content'], $date, $ID); //reply테이블의 ID가 rno변수에 저장된 값의 content를 선택해서 값 저장

    if($sql3->execute()){
    ?>
        <script>
            type="text/javascript">alert('수정되었습니다.'); 
            location.replace("qna_view.php?ID=<?= $Q_num?>");
        </script>
    <?php    
    }
    else {
        echo "fail";
        }
?>