<?php
    session_start();
    include '../../config.php';

    $connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("fail");
    $ID = $_GET['ID'];
    $query = $connect->prepare("SELECT title, content, date, hit, userID, pw, file_name, file_path, lock_post from QnA WHERE ID = ?");
    $query->bind_param('i', $ID);
    $query->execute();
    $result = $query->get_result();
    $rows = $result->fetch_assoc();


    if($rows['lock_post'] == '1'){
        if(($_SESSION['userID'] == 'Admin') || ($_SESSION['userID'] == $rows['userID'])) {
        }else{
            echo "<script>alert('해당 글을 볼 수 없습니다.'); window.location.href='qna_index.php';</script>";
        }
            $sql = $connect->prepare("SELECT * FROM Q_reply WHERE Q_num = ?");
            $sql->bind_param('i', $ID);
            $sql->execute();
            $result2 = $sql->get_result();
            $row_count = $result2->num_rows;
     }

     // 조회수 데베에 데이터 추가 코드
     $ip = $_SERVER['REMOTE_ADDR'];
     $time = date('Y-m-d H:i:s');
     
     // 사용자가 해당 게시판에 처음 들어간 경우 조회수 바로 갱신.
     $first = $connect->prepare("SELECT * FROM QnA_hit WHERE ip = ?");
     $first->bind_param('s', $ip);
     $first->execute();
     $first_result = $first->get_result();
 
     if($first_result->num_rows == 0){  //$first_result의 행의 개수
         $query2 = $connect->prepare("INSERT INTO QnA_hit (board_num, time, ip, hit_ok) VALUES (?, ?, ?, 1)");
         $query2->bind_param('sss', $ID, $time, $ip);
         $query2->execute();
 
         $update_hit = $connect->prepare("UPDATE QnA SET hit = hit + 1 WHERE ID = ?");
         $update_hit->bind_param('i', $ID);
         $update_hit->execute();
     }else{
        $query3 = $connect->prepare("SELECT * FROM QnA_hit WHERE ip = ? AND hit_ok = 0");
        $query3-> bind_param('s', $ip);
        $query3->execute();
        $result3 = $query3->get_result(); 

        if($result3->num_rows == 0){
            $queryf2 = $connect->prepare("INSERT INTO QnA_hit (board_num, time, ip) VALUES (?, ?, ?)");
            $queryf2->bind_param('sss', $ID, $time, $ip);
            $queryf2->execute();   
        }else{
            while ($row2 = $result3->fetch_assoc()){
                // time은 문자열 형태로 저장되어 있으니 DateTime 객체로 바꿈.
               $time = new DateTime($row2['time']);
               $now = new DateTime(); // 현재 시간
               $interval = $now->diff($time);  // 비교(시간 차이의 값이 다 독립적임)  시간: h, 분: i, 초: s
   
               if($interval->h >= 1){
                   $update_ok = $connect->prepare("UPDATE QnA_hit SET hit_ok = 1 WHERE ID = ? ");
                   $update_ok->bind_param('i', $row2['ID']);
                   $update_ok->execute();
   
                   $update_hit = $connect->prepare("UPDATE QnA SET hit = hit + 1 WHERE ID = ?");
                   $update_hit->bind_param('i', $ID);
                   $update_hit->execute();
               }
                // 1시간으로 할거면 계산 필요 X.
                /*
                $time_diff = $interval->h * 3600 + $interval->i * 60 + $interval->s;  // 전체 차이를 초로 표현하려면 시간 + 분 + 초로 합쳐야 함. 
    
                if($time_diff >= 10){  // 테스트용 
                    $update_ok = $connect->prepare("UPDATE QnA_hit SET hit_ok = 1 WHERE ID = ?");
                    $update_ok->bind_param('i', $row2['ID']);
                    $update_ok->execute();
    
                    $update_hit = $connect->prepare("UPDATE QnA SET hit = hit + 1 WHERE ID = ?");
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
    <link rel="stylesheet" type="text/css" href="./qna_view.css">
    <link rel="stylesheet" type="text/css" href="../../BBS/css/jquery-ui.css" />
        <script type="text/javascript" src="../../BBS/js/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" src="../../BBS/js/jquery-ui.js"></script>
        <script type="text/javascript" src="../../BBS/js/common.js"></script>
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
                
                        <button type = "button" onclick="location.href='./write/download.php?file=<?php echo urlencode($rows['file_name']); ?>'">파일 다운로드</button>
                        </td>
                        </tr>
                <?php endif; ?>
    </div>
    <div class="view_btn">
        <button class="w-btn w-btn-gray" onclick="location.href='qna_index.php'">목록으로</button>
    <?php
    if((($_SESSION['userID']) == $rows['userID']) && $row_count == 0){
    ?>    
        <button class="w-btn w-btn-gray" onclick="location.href='write/qna_modify.php?ID=<?=$ID?>&userID=<?=$_SESSION['userID']?>'">수정</button>
        <button class="w-btn w-btn-gray" onclick="location.href='write/qna_delete.php?ID=<?=$ID?>'">삭제</button>       
    </div>
    <?php 
        }
    ?>
</div>
   <!--- 댓글 불러오기 -->
<div class="reply_view" >
    <!-- 댓글 입력 폼 -->
    <?php 
    if($_SESSION['userID'] == 'Admin'){  ?>
        <div class="dap_ins">
                <form action="qna_reply_ok.php?ID=<?php echo $ID; ?>" method="post">
                    <input type="hidden" name="userID" value="<?php echo $_SESSION['userID']; ?>"> <!-- 세션에 저장된 아이디로 설정 -->
                    <td><?php echo  $_SESSION['userID'] ?></td> <!-- $rows['userID']로 작성자 아이디를 출력 -->
                    <div style="margin-top:10px; ">
                        <textarea name="content" class="reply_content" id="re_content"></textarea>
                        <button id="rep_bt" class="re_bt">댓글</button>
                    </div>
                </form>
        </div> <?php
    }  ?>
    <h3>댓글목록</h3>
    <?php
    $sql3 = mysqli_query($connect, "SELECT * FROM Q_reply WHERE Q_num = $ID ORDER BY ID DESC");
    while ($reply = mysqli_fetch_assoc($sql3)) { ?>
            <div class="dap_lo">
                <div><b><?php echo $reply['userID']; ?></b></div> 
                <div class="dap_to comt_edit"><?php echo nl2br($reply['content']); ?></div>
                <div class="rep_me dap_to"><?php echo $reply['date']; ?></div>
                <?php
                if ($_SESSION['userID'] == 'Admin') {  ?>
                        <div class="rep_me rep_menu">
                            <a class="dat_edit_bt" href="#">수정</a>
                            <a class="dat_delete_bt" href="Q_reply_delete.php?ID=<?=$reply['ID']?>">삭제</a>
                        </div>
                        
                        <!-- 댓글 수정 폼 dialog -->
                        <div class="dat_edit">
                            <form method="post" action="Q_reply_modify_ok.php">
                                <input type="hidden" name="ID" value="<?php echo $reply['ID']; ?>" />
                                <input type="hidden" name="Q_num" value="<?php echo $ID; ?>">  
                                <textarea name="content" class="dap_edit_t"><?php echo $reply['content']; ?></textarea>
                                <input type="submit" value="수정하기" class="re_mo_bt">
                            </form>
                        </div> <?php  
                }  ?>
            <div id="foot_box"></div>
            </div>
            <?php
    }
    ?>
</div> <!--- 댓글 불러오기 끝 --> 
    <div id="foot_box"></div>
</body>
</html>