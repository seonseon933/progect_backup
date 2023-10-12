<?php
    session_start();
    include '../config.php';
    $connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("fail");
    $ID = $_GET['ID'];
    $query = $connect->prepare("SELECT title, content, date, hit, userID, file_name, file_path FROM noticeBoard WHERE ID = ?");
    $query->bind_param('i', $ID);
    $query->execute();
    $result = $query->get_result();
    $rows = $result->fetch_assoc();
    // 조회수 데베에 데이터 추가 코드
    $ip = $_SERVER['REMOTE_ADDR'];
    $time = date('Y-m-d H:i:s');
    
    // 사용자가 해당 게시판에 처음 들어간 경우 조회수 바로 갱신.
    $first = $connect->prepare("SELECT * FROM notice_hit WHERE ip = ?");
    $first->bind_param('s', $ip);
    $first->execute();
    $first_result = $first->get_result();

    if($first_result->num_rows == 0){  //$first_result의 행의 개수
        $query2 = $connect->prepare("INSERT INTO notice_hit (board_num, time, ip, hit_ok) VALUES (?, ?, ?, 1)");
        $query2->bind_param('sss', $ID, $time, $ip);
        $query2->execute();

        $update_hit = $connect->prepare("UPDATE noticeBoard SET hit = hit + 1 WHERE ID = ?");
        $update_hit->bind_param('i', $ID);
        $update_hit->execute();
    }else{
        $query3 = $connect->prepare("SELECT * FROM notice_hit WHERE ip = ? AND hit_ok = 0");
        $query3-> bind_param('s', $ip);
        $query3->execute();
        $result3 = $query3->get_result(); // 결과 얻기

        if($result3->num_rows == 0){
            $queryf2 = $connect->prepare("INSERT INTO notice_hit (board_num, time, ip) VALUES (?, ?, ?)");
            $queryf2->bind_param('sss', $ID, $time, $ip);
            $queryf2->execute();

        }else{
            while ($row2 = $result3->fetch_assoc()){
                // time은 문자열 형태로 저장되어 있으니 DateTime 객체로 바꿈.
                $time = new DateTime($row2['time']);
                $now = new DateTime(); // 현재 시간
                $interval = $now->diff($time);  // 비교(시간 차이의 값이 다 독립적임)  시간: h, 분: i, 초: s
                
                if($interval->h >= 1){
                    $update_ok = $connect->prepare("UPDATE notice_hit SET hit_ok = 1 WHERE ID = ? ");
                    $update_ok->bind_param('i', $row2['ID']);
                    $update_ok->execute();

                    $time_r = date('Y-m-d H:i:s');
                    $query4 = $connect->prepare("INSERT INTO notice_hit (board_num, time, ip) VALUES (?, ?, ?)");
                    $query4->bind_param('sss', $ID, $time_r, $ip);
                    $query4->execute();
    
                    $update_hit = $connect->prepare("UPDATE noticeBoard SET hit = hit + 1 WHERE ID = ?");
                    $update_hit->bind_param('i', $ID);
                    $update_hit->execute();
                }
                /*
                $time_diff = $interval->h * 3600 + $interval->i * 60 + $interval->s;  // 전체 차이를 초로 표현하려면 시간 + 분 + 초로 합쳐야 함. 
                if($time_diff >= 10){  // 테스트용 
                    $update_ok = $connect->prepare("UPDATE notice_hit SET hit_ok = 1 WHERE ID = ?");
                    $update_ok->bind_param('i', $row2['ID']);
                    $update_ok->execute();

                    $time_r = date('Y-m-d H:i:s');
                    $query4 = $connect->prepare("INSERT INTO notice_hit (board_num, time, ip) VALUES (?, ?, ?)");
                    $query4->bind_param('sss', $ID, $time_r, $ip);
                    $query4->execute();
    
                    $update_hit = $connect->prepare("UPDATE noticeBoard SET hit = hit + 1 WHERE ID = ?");
                    $update_hit->bind_param('i', $ID);
                    $update_hit->execute();

                }
                */
            }
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>HWEB</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="./notice_view.css">
</head>
<body>
    <table class="view_table" align="center">
        <tr>
            <td colspan="4" class="view_title"><?php echo $rows['title']?></td>
        </tr>
        <tr>
            <td class="view_id">작성자</td>
            <td class="view_id2"><?php echo $rows['userID']?></td>
            <td class="view_hit">조회수</td>
            <td class="view_hit2"><?php echo $rows['hit']?></td>  
        </tr>
        <tr>
            <td colspan="4" class="view_content" valign="top">
                <?php echo $rows['content']?>
            </td>
        </tr>

    </table>
    <!-- MODIFY & DELETE -->
    <div class = "view_btn">
        <?php if (!empty($rows['file_path'])): ?>
                        <tr>
                        <td colspan="4" class="view_file">
                
                        <button class="w-btn w-btn-gray" type = "button" onclick="location.href='/notice/write/download.php?file=<?php echo urlencode($rows['file_name']); ?>'">파일 다운로드</button>
                        </td>
                        </tr>
                <?php endif; ?>
    </div>
    <div class="view_btn">
        <button class="w-btn w-btn-gray" onclick="location.href='notice_index.php'">목록으로</button>
    <?php
    if(($_SESSION['userID']) == 'Admin'){
    ?>    
        <button class="w-btn w-btn-gray" onclick="location.href='write/notice_modify.php?ID=<?=$ID?>&userID=<?=$_SESSION['userID']?>'">수정</button>
        <button class="w-btn w-btn-gray" onclick="location.href='write/notice_delete.php?ID=<?=$ID?>'">삭제</button>       
    </div>
    <?php 
        }
    ?>
</body>
</html>