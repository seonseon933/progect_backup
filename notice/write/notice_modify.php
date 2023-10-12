<link rel="stylesheet" type="text/css" href="../../button.css">
<?php    
                session_start();   
                include '../../config.php'; 
                $connect= new mysqli($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("connect fail");
                
                $URL = '../notice_index.php';

                if(($_SESSION['userID'] != 'Admin')) { 
                        ?>
                        <script> alert("관리자만 가능한 페이지입니다."); location.replace("<?php echo $URL?>"); </script>
                <?php
                }

                $userID = $_GET['userID']; //사용자 정보
                $ID = $_GET['ID']; //번호

                $query = $connect->prepare("SELECT title, content, date, userID FROM noticeBoard WHERE ID = ?");
                $query->bind_param('i', $ID);
                $query->execute();
                $result = $query->get_result();
                $rows = $result->fetch_assoc();

                $title = $rows['title'];
                $content = $rows['content'];
                $usrid = $rows['userID'];
 
                $URL = '../notice_index.php';
 
                if(!isset($_SESSION['userID']) && ($_SESSION['userID'] !=$usrid)) {
        ?>              <script>
                                alert("권한이 없습니다.");
                                location.replace("<?php echo $URL?>");
                        </script>
        <?php   }
                else if($_SESSION['userID']==$usrid) {
        ?>
        <form method = "POST" action = "modify_action.php">
        <table  style="padding-top:50px" align = center width=700 border=0 cellpadding=2 >
                <tr>
                <td height=20 align= center bgcolor=#ccc><font color=white> 글 수정</font></td>
                </tr>
                <tr>
                <td bgcolor=white>
                <table class = "table2">
                <tr>
                        <td>작성자</td>
                        <td><input type="hidden" name="userID" value="<?=$_SESSION['userID']?>"><?=$_SESSION['userID']?></td>
                        </tr>
 
                        <tr>
                        <td>제목</td>
                        <td><input type = text name = title size=60 value="<?=$title?>"></td>
                        </tr>
 
                        <tr>
                        <td>내용</td>
                        <td><textarea name = content cols=85 rows=15><?=$content?></textarea></td>
                        </tr>
 
                        </table>
 
                        <center>
                        <input type="hidden" name="ID" value="<?=$ID?>">
                        <input class="w-btn w-btn-gray" type = "submit" value="작성">
                        </center>
                </td>
                </tr>
        </table>
        <?php   }
                else {
        ?>              <script>
                                alert("권한이 없습니다.");
                                location.replace("<?php echo $URL?>");
                        </script>
        <?php   }
        ?>