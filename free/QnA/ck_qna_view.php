<?php
	include '../../config.php';
    $connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("fail");
?>
<link rel="stylesheet" type="text/css" href="../../BBS/css/jquery-ui.css" />
<script type="text/javascript" src="../../BBS/js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="../../BBS/js/jquery-ui.js"></script>
<script type="text/javascript">
	$(function(){
		$("#writepass").dialog({
		 	modal:true,
		 	title:'비밀글입니다.',
		 	width:400,
	 	});
	});
</script>
<?php

$ID = $_GET['ID']; /* 공지 DB의 ID */
$sql = $connect->prepare("SELECT * FROM QnA WHERE ID = ?");
$sql->bind_param('i', $ID);
$sql->execute();
$result = $sql->get_result();
$qna = $result->fetch_array();

?>
<div id='writepass'>
	<form action="" method="post">
 		<p>비밀번호<input type="password" name="pw_chk" /> <input type="submit" value="확인" /></p>
 	</form>
</div>
	 <?php
	 	$q_pw = $qna['pw']; 
        
	 	if(isset($_POST['pw_chk']))
	 	{
	 		$pwk = $_POST['pw_chk']; 
			 if(password_verify($pwk,$q_pw)) 
			 {
				 $pwk == $q_pw;
			 ?>
				<script type="text/javascript">location.replace("qna_view.php?ID=<?php echo $qna['ID']?>");</script><!-- 비번 같으면 다시 view로 슝~ -->
			<?php 
			}else{ ?>
                <script >alert($q_pw);</script>
				<script type="text/javascript">alert('비밀번호가 틀립니다');</script>
			<?php } } ?>
