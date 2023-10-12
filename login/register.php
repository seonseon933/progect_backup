<!doctype html>
<html lang="ko">
    <head>
        <meta charset="UTF-8">
        <title>HWEB Registration</title>
        <link rel="stylesheet" type="text/css" href="login.css">
    </head>
    <body>
        <div class = "login-wrapper">
            <h2>회원가입</h2>
            <form method="POST" action="phpMailer_ok.php" id="login-form">
                <input type="text" name="userID" placeholder="ID" required>
                <input type="password" name="userPW" placeholder="Password" required>
                <input type="text" name="userName" placeholder="Nickname" required>
                <input type="text" name="userEmail" placeholder="Email" required>
                <input type="submit" value="가입하기">
            </form>
        </div>
    </body>
</html>