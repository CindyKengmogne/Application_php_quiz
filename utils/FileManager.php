<?php
class FileManager {
    public function uploadFile($file, $targetDir) {
        $targetFile = $targetDir . basename($file["name"]);
        return move_uploaded_file($file["tmp_name"], $targetFile);
    }

    public function deleteFile($filePath) {
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }
}

?>