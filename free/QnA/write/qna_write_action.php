<?php
    include '../../../config.php';
    $connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("connect fail");

    $pw =  password_hash($_POST['pw'], PASSWORD_DEFAULT); // 암호화!! 
    $userID = $_POST['userID'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = date('Y-m-d H:i:s');
    
    $URL = '../qna_index.php';
    
    if(isset($_POST['lockpost'])){
        $lock_post = '1';
    }else{
        $lock_post = '0';
    }
    
    // 파일 업로드 처리    // 파일명이 한글일 경우 file_path에 아무것도 담기지 않고, 파일 업로드 또한 되지 않음. -> utf-8해도 안됨
    $uploaded_file_name = $_FILES['myfile']['name'];
    if ($_FILES['myfile']['error'] === UPLOAD_ERR_OK) {

        $uploaded_file_name = $_FILES['myfile']['name'];
        $uploaded_file_name_tmp = $_FILES['myfile']['tmp_name'];
        $upload_folder = "./uploads/";
        $file_path = $upload_folder . $uploaded_file_name;
    
        if (move_uploaded_file($uploaded_file_name_tmp, $file_path)) {
            
        } else {
            echo "<p>파일 업로드에 실패하였습니다.</p>";
        }
    } else {
        // 파일 업로드 실패한 경우도 고려하여 여기에 처리할 내용 추가 가능
    }
    $query = $connect->prepare("INSERT INTO QnA (title, content, userID, pw, date, hit, file_name, file_path, lock_post) 
     VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?)");
    $query->bind_param('sssssssi', $title, $content, $userID, $pw, $date, $uploaded_file_name, $file_path, $lock_post);
    
    if ($query->execute()) {
        echo "<script>
                alert('글이 등록되었습니다.');
                location.replace('$URL');
              </script>";
    } else {
        echo "FAIL";
    }
    
    mysqli_close($connect);
?>