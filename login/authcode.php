<?php
    session_start();
    include '../config.php';
    
    $connect = mysqli_connect($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("fail");
    $expire = 180; // 유효시간 : 3분.
?>
<link rel="stylesheet" type="text/css" href="../BBS/css/jquery-ui.css" />
<script type="text/javascript" src="../BBS/js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="../BBS/js/jquery-ui.js"></script>
<script type="text/javascript">
    $(function(){
		$("#writepass").dialog({
		 	modal:true,
		 	title:'이메일 인증 코드 입력란.',
		 	width:400,
	 	});
	});
</script>
<div id='writepass'>
	<form action="" method="post">
 		<p>인증 코드<input type="password" name="pw_chk" /> <input type="submit" value="확인" /></p>
 	</form>
</div>

<?php
    if(time() - $_SESSION['auth_code']['created_at'] > $expire){
        unset($_SESSION['auth_code']);  // 세션 변수 없애기
        unset($_SESSION['userID']);
        unset($_SESSION['userPW']);
        unset($_SESSION['userName']);
        unset($_SESSION['userEmail']);
        echo "<script>alert('인증 코드의 유효 시간이 만료되었습니다.'); window.location.href='./register.php';</script>";
        exit;
    }
    if(isset($_POST['pw_chk']))
    {
        $aucode = $_SESSION['auth_code']['code'];
        $pwchk = $_POST['pw_chk'];
        if($aucode == $pwchk){

            $userID = $_SESSION['r_userID'];
            $userPW = $_SESSION['r_userPW'] ;
            $userName = $_SESSION['r_userName']; 
            $userEmail = $_SESSION['r_userEmail'];

            $dbconn = "INSERT INTO user (userID, userPW, userName, userEmail) VALUES ('$userID', '$userPW', '$userName', '$userEmail');";
            mysqli_query( $connect, $dbconn );

            echo "<script>alert('회원가입이 완료되었습니다.'); window.location.href='./login.php';</script>";
        }
        else{ ?>
            <script >alert($pwchk);</script>
			<script type="text/javascript">alert('비밀번호가 틀립니다');</script>   <?php
        }
    } ?>
