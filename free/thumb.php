<?php
// thumb.php

    session_start();
    include '../config.php';
    $connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("connect fail");

    if (isset($_GET['ID'])) {
        $ID = $_GET['ID'];
        
        if (!isset($_SESSION['voted_posts'])) {
            $_SESSION['voted_posts'] = array();
        }

        if (in_array($ID, $_SESSION['voted_posts'])) {
            echo "<script>
                    alert('이미 추천한 글입니다.');
                    location.replace('free_view.php?ID=$ID');
                </script>";
            exit();
        }
        $query = $connect->prepare('UPDATE freeBoard SET thumb = thumb + 1 WHERE ID = ?');
        $query->bind_param('i', $ID);
        $result = $query->execute();

        if ($result) {
            
            $_SESSION['voted_posts'][] = $ID;

            echo "<script>
                    alert('추천되었습니다.');
                    location.replace('free_view.php?ID=$ID');
                </script>";
        } else {
            echo "FAIL";
        }
    }

    mysqli_close($connect);
?>