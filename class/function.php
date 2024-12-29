<?php
require 'connection.php';

if (isset($_POST["submit"])) {
    // Retrieve the name from the form
    $name = $_POST["name"];
    
    // Check if an image was uploaded
    if ($_FILES["image"]["error"] === 4) {
        echo "<script>alert('Image Does Not Exist');</script>";
    } else {
        // Get image details
        $fileName = $_FILES["image"]["name"];
        $fileSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];

        // Define valid image extensions
        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));

        // Validate image extension
        if (!in_array($imageExtension, $validImageExtension)) {
            echo "<script>alert('Invalid Image Extension');</script>";
        }
        // Validate image size
        elseif ($fileSize > 1000000) { // 1MB limit
            echo "<script>alert('Image Size Is Too Large');</script>";
        } else {
            // Generate a unique name for the image
            $newImageName = uniqid();
            $newImageName .= '.' . $imageExtension;

            // Move the uploaded file to the 'img/' directory
            move_uploaded_file($tmpName, 'img/' . $newImageName);

            // Insert the data into the database
            $query = "INSERT INTO tb_upload VALUES('', '$name', '$newImageName')";
            mysqli_query($conn, $query);

            // Notify the user and redirect to the data page
            echo "<script>
                    alert('Successfully Added');
                    document.location.href = 'data.php';
                  </script>";
        }
    }
}
?>
