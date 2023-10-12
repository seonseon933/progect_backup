<?php 
  session_start();

  include '../config.php';
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
  
  require '../PHPMailer/src/Exception.php';
  require '../PHPMailer/src/PHPMailer.php';
  require '../PHPMailer/src/SMTP.php';

  $userID = $_POST['userID'];
  $userEmail = $_POST['userEmail'];
  $userID_e = "";

  if(isset($userID) && !empty($userID) && isset($_POST['userPW']) && isset($_POST['userName']) && isset($userEmail) && !empty($userEmail)){
    $conn = mysqli_connect($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("fail");
    $sql = "SELECT userID FROM user WHERE userID = '$userID';";
    $result = mysqli_query( $conn, $sql );
    while ( $row = mysqli_fetch_array( $result ) ) {
        $userID_e = $row[ 'userID' ];
    }
    if ( $userID == $userID_e ) {
      echo "<script>alert('아이디가 중복되었습니다.'); window.location.href='./register.php';</script>";
      exit;
    }
    if(!preg_match("/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i", $userEmail)){
      echo "<script>alert('이메일이 형식에 맞지 않습니다.'); window.location.href='./register.php';</script>";
      exit;
    }

    $_SESSION['r_userID'] = $userID;
    $_SESSION['r_userPW'] = password_hash($_POST['userPW'], PASSWORD_DEFAULT);
    $_SESSION['r_userName'] = $_POST['userName'];
    $_SESSION['r_userEmail'] = $userEmail;

    $auth_code = rand(100000, 999999);
    $_SESSION['auth_code'] = array('code' => $auth_code, 'created_at' => time());  // 세션 만든 시간 저장.

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8'; 
    $mail->isSMTP();  // SMTP 메일 서버를 사용해 메일을 보내도록 지시.
    $mail->Host        = 'smtp.gmail.com';  // 실제 SMTP 메일 서버의 주소
    $mail->SMTPAuth    = true;
    $mail->Username    = $MAIL_USER;
    $mail->Password    = $MAIL_PW;
    $mail->SMTPSecure  = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port        = 587;
    $mail->setFrom($MAIL_USER, 'HWEB'); 
    $mail->addAddress($userEmail, $userID);  // 나중에 변수값 넣기. 
    $mail->isHTML(true);
    $mail->Subject     = 'HWEB 이메일 인증 코드';  // 제목: HWEB 이메일 인증 번호.
    $mail->Body        = 'HWEB 인증 코드:  <b>'.$auth_code.'</b>';  // 인증 번호 넣기.
    $mail->send();
    echo "<script>alert('해당 이메일에 인증 코드를 전송하였습니다!'); window.location.href='./authcode.php';</script>";
  }
?>