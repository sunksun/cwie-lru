<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $uploadDir = 'uploads/';
    $fileName = basename($_FILES['image']['name']);
    $uploadFile = $uploadDir . $fileName;

    // Ensure the upload directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Move the uploaded file to the upload directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        // Function to resize images while maintaining aspect ratio
        function resizeImage($file, $maxWidth, $maxHeight, $output)
        {
            list($originalWidth, $originalHeight) = getimagesize($file);
            $ratio = $originalWidth / $originalHeight;

            if ($maxWidth / $maxHeight > $ratio) {
                $newWidth = $maxHeight * $ratio;
                $newHeight = $maxHeight;
            } else {
                $newHeight = $maxWidth / $ratio;
                $newWidth = $maxWidth;
            }

            $src = imagecreatefromjpeg($file);
            $dst = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
            imagejpeg($dst, $output);
            imagedestroy($src);
            imagedestroy($dst);
        }

        // Define the sizes and output file names
        $sizes = [
            ['width' => 370, 'height' => 360, 'output' => $uploadDir . '370x360_' . $fileName],
            ['width' => 1024, 'height' => 683, 'output' => $uploadDir . '1024x683_' . $fileName]
        ];

        // Resize and save the images
        foreach ($sizes as $size) {
            resizeImage($uploadFile, $size['width'], $size['height'], $size['output']);
        }

        echo "The image has been uploaded and resized successfully.";
    } else {
        echo "There was an error uploading the file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload and Resize Image</title>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="image">Choose an image to upload:</label>
        <input type="file" name="image" id="image" required>
        <input type="submit" value="Upload Image">
    </form>
</body>

</html>