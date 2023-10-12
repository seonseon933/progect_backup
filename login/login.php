<?php
    session_start();

    include '../config.php';

    $userID = $_POST['userID'];
    $userPW = $_POST['userPW'];
    $roll = $_POST['roll'];

    if ( !is_null( $userID ) && !is_null($userPW)) {

        $conn=mysqli_connect($DB_URL,$DB_USERNAME, $DB_PW, 'User');
        
        if(($roll == "Admin")){
            $isAdmin = 'Admin';
            $sql2 = "SELECT userPW FROM user WHERE userID = 'admin' AND roll = '$roll';";
            $result2 = mysqli_query( $conn, $sql2 );
            while ( $row2 = mysqli_fetch_array( $result2 ) ) {
                $userPW_e2 = $row2[ 'userPW' ];
            }

            if ( is_null($userPW_e2) ) {
                $wu = 1;
            }else{
                if(password_verify($userPW, $userPW_e2)){
                    $_SESSION['userID'] = $isAdmin;
                    if (isset($_SESSION['userID'])){
                        echo "<script>alert('로그인 성공.'); window.location.href='../admin_ok/admin_index.php';</script>";
                    }
                    
                }else{
                    $wp = 1;    
                }
            }
        }
        else{
            $sql = "SELECT userPW FROM user WHERE userID = '$userID' AND roll = '$roll';";
            $result = mysqli_query( $conn, $sql );
            while ( $row = mysqli_fetch_array( $result ) ) {
                $userPW_e = $row[ 'userPW' ];
            }
            if ( is_null($userPW_e) ) {
                $wu = 1;
            }else{
                if(password_verify($userPW, $userPW_e)){
                    $_SESSION['userID'] = $userID;     // 세션 userID에 해당 사용자 userID 넣기
                    if (isset($_SESSION['userID'])){
                        echo "<script>alert('로그인 성공.'); window.location.href='../user_ok/user_index.php';</script>";
                    }
                }else{
                    $wp = 1;
                }
            }
        }
        
    }
?>
<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>HWEB Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
<div class = "login-wrapper">
    <h2>HWEB</h2>
    <form method="POST" action="login.php" id="login-form">
        <div>
            admin<input type="radio" name="roll" value="Admin"/>
            user<input type="radio" name="roll" value="user" checked />
        </div>
        <input type="text" name="userID" placeholder="ID" required>
        <input type="password" name="userPW" placeholder="Password" required>
        <input type="submit" value="Login">
        <a href="register.php" target="_blank" rel="noopener noreferrer">회원 가입</a>
        <?php
        if ( $wu == 1 ) {
          echo "<script>alert('사용자이름이 존재하지 않습니다.');</script>";
        }
        if ( $wp == 1 ) {
          echo "<script>alert('비밀번호가 틀렸습니다.');</script>";
        }
      ?>
    </form>
</div>
</body>
</html>