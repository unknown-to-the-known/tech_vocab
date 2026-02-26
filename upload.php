<?php

if(isset($_FILES['video'])){

    $uploadDir = "uploads/";

    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_compressed.mp4";
    $targetFile = $uploadDir . $fileName;

    if(move_uploaded_file($_FILES['video']['tmp_name'], $targetFile)){
        echo "Success";
    } else {
        echo "Failed";
    }

} else {
    echo "No file received";
}
?>