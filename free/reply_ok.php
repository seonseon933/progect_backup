<?php
    include '../config.php';
	$connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("fail");

    $board_num = $_GET['ID'];
    $date = date('Y-m-d H:i:s');
    $userID = $_POST['userID'];
    $content = $_POST['content'];

    if($board_num && $userID && $content) {
        $query = $connect->prepare("INSERT INTO reply (board_num, userID, content, date) VALUES (?, ?, ?, ?)");
        $query->bind_param('ssss', $board_num, $userID, $content, $date);
        $query->execute();
        echo "<script>alert('댓글이 작성되었습니다.');
        location.href='free_view.php?ID=$board_num';</script>";
    } else{
        echo "<script>alert('댓글 작성에 실패했습니다.'); 
        history.back();</script>";
    }
	
?>