<!-- | No | 제목 | 작성자 | 날짜 | 조회순 | -->
<?php
                session_start();
                $URL = "../notice_index.php";
                if($_SESSION['userID'] != 'Admin') {
        ?>
 
                <script>
                        alert("관리자만 공지 글을 작성할 수 있습니다.");    // 해야할 것: free, QnA 등 게시판에서 링크를 통해 관리자처럼 행동할 수 있음.
                        location.replace("<?php echo $URL?>");
                </script>
        <?php
                }
        ?>

<html>
<head>
        <link rel="stylesheet" type="text/css" href="../../button.css">
        <title>HWEB</title>
        <meta charset = 'utf-8'>
</head>
<style>
        table.table2{
                border-collapse: separate;
                border-spacing: 1px;
                text-align: left;
                line-height: 1.5;
                border-top: 1px solid #ccc;
                margin : 20px 10px;
        }
        table.table2 tr {
                 width: 50px;
                 padding: 10px;
                font-weight: bold;
                vertical-align: top;
                border-bottom: 1px solid #ccc;
        }
        table.table2 td {
                 width: 100px;
                 padding: 10px;
                 vertical-align: top;
                 border-bottom: 1px solid #ccc;
        }
</style>
<body>
        <form enctype="multipart/form-data" method = "POST" action = "notice_write_action.php" >
        <table  style="padding-top:50px" align = center width=700 border=0 cellpadding=2 >
                <tr>
                <td height=20 align= center bgcolor=#ccc><font color=white> 글쓰기</font></td>
                </tr>
                <tr>
                <td bgcolor=white>
                <table class = "table2">
                        <tr>
                        <td>작성자</td>
                        <td><input type = hidden name = "userID"  size=20 value="<?=$_SESSION['userID']?>"><?=$_SESSION['userID']?> </td>
                        </tr>
 
                        <tr>
                        <td>제목</td>
                        <td><input type = text name = title size=60></td>
                        </tr>
 
                        <tr>
                        <td>내용</td>
                        <td><textarea name = content cols=85 rows=15></textarea></td>
                        </tr>
                        </table>

                        <p>파일 이름이 영어인 파일만 업로드.</p>
                        <p><input type="file" name="myfile"></p>
                        <center>
                        <input class="w-btn w-btn-gray" type = "submit" value="작성">
                        </center>
                </td>
                </tr>
        </table>
        </form>
</body>
</html>