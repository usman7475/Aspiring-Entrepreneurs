<?php
if ($_FILES['upload']) {
    $file = $_FILES['upload'];
    $uploadDir = "uploads/";
    $fileName = basename($file["name"]);
    $targetFile = $uploadDir . time() . "_" . $fileName;

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        $funcNum = $_GET['CKEditorFuncNum'];
        $url = $targetFile;
        $message = 'Image uploaded successfully';
        echo "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
    } else {
        echo "File upload failed!";
    }
}
?>
