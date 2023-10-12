<?php
if (isset($_GET['file'])) {
    $file = urldecode($_GET['file']);
    $file_path = './uploads/' . $file;

    if (file_exists($file_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));

        readfile($file_path);
        exit;
    } else {

        die('파일이 존재하지 않습니다.');
    }
} else {
    die('파일을 찾을 수 없습니다.');
}
?>  