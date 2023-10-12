<?php
    include '../config.php';
    $conn = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("connect fail");

    $ID = $_GET['ID'];
    $sql = $conn->prepare("UPDATE reply SET content='삭제된 댓글입니다.' WHERE ID = ?");
    $sql->bind_param('i', $ID);

    if ($sql->execute()) {
        echo "<script>alert('해당 댓글은 삭제되었습니다.');</script>";
    } else {
        // 오류 발생 시 메시지 출력
        echo "<script>alert('댓글 삭제 실패: " . mysqli_error($conn) . "');</script>";
    }

    // 이전 페이지로 돌아가기
    echo "<script>history.go(-1);</script>";
?>