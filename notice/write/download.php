<?php
if (isset($_GET['file'])) {
    $file = urldecode($_GET['file']); // 파일 경로 디코딩
    $file_path = './uploads/' . $file; // 파일 경로 설정 (이 예시에서는 uploads 폴더에 파일이 저장되어 있다고 가정합니다.)

    // 파일이 존재하는지 확인
    if (file_exists($file_path)) {
        // 다운로드를 위한 헤더 설정
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));

        // 파일을 클라이언트로 출력하여 다운로드
        readfile($file_path);

        // 다운로드가 완료되면 종료
        exit;
    } else {
        // 파일이 존재하지 않으면 에러 메시지 출력
        die('파일이 존재하지 않습니다.');
    }
}
?>