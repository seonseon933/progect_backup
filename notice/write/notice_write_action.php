<?php
    session_start();
    include '../../config.php';
    $connect = new mysqli($DB_URL,$DB_USERNAME, $DB_PW, 'User') or die("connect fail");

    $userID = $_POST['userID'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = date('Y-m-d H:i:s');
    
    $URL = '../notice_index.php';

    if(($_SESSION['userID'] != 'Admin')) { 
        ?>
        <script> alert("관리자만 가능한 페이지입니다."); location.replace("<?php echo $URL?>"); </script>
<?php
    }
    
    // 파일 업로드 처리    // 파일명이 한글일 경우 file_path에 아무것도 담기지 않고, 파일 업로드 또한 되지 않음. 왜 그러는거지.
    
    if ($_FILES['myfile']['error'] === UPLOAD_ERR_OK) {

        $uploaded_file_name = $_FILES['myfile']['name']; // 업로드한 파일 이름
        $uploaded_file_name_tmp = $_FILES['myfile']['tmp_name']; // 임시 파일의 경로
        $upload_folder = "./uploads/"; 
        $file_path = $upload_folder . $uploaded_file_name;
    
        if (move_uploaded_file($uploaded_file_name_tmp, $file_path)) {
            
        } else {
            echo "<p>파일 업로드에 실패하였습니다.</p>";
        }
    } 
    $query = $connect->prepare("INSERT INTO noticeBoard (title, content, userID, date, hit, file_name, file_path) 
    VALUES (?, ?, ?, ?, 0, ?, ?)");
    $query->bind_param('ssssss',$title, $content, $userID, $date, $uploaded_file_name, $file_path);

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