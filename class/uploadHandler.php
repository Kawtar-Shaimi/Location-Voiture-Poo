<?php
function handleFileUpload($file, $uploadDir = 'uploads/', $maxFileSize = 1000000, $validExtensions = ['jpg', 'jpeg', 'png']) {
    // Check for file upload errors
    if ($file["error"] === 4) {
        return [
            'success' => false,
            'message' => 'No file uploaded.'
        ];
    }

    $fileName = $file["name"];
    $fileSize = $file["size"];
    $tmpName = $file["tmp_name"];

    // Get file extension and validate
    $imageExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($imageExtension, $validExtensions)) {
        return [
            'success' => false,
            'message' => 'Invalid file extension. Allowed: ' . implode(', ', $validExtensions)
        ];
    }

    if ($fileSize > $maxFileSize) {
        return [
            'success' => false,
            'message' => 'File size exceeds the limit of ' . ($maxFileSize / 1000000) . ' MB.'
        ];
    }

    // Ensure the upload directory exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!is_writable($uploadDir)) {
        return [
            'success' => false,
            'message' => 'Upload directory is not writable.'
        ];
    }

    // Generate unique file name and move file
    $newFileName = uniqid() . '.' . $imageExtension;
    $destination = $uploadDir . $newFileName;

    if (move_uploaded_file($tmpName, $destination)) {
        return [
            'success' => true,
            'fileName' => $newFileName
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Failed to move uploaded file.'
        ];
    }
}