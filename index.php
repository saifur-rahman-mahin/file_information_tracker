<?php
// Check if the file was uploaded without errors
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileInput"])) {
    // File information
    $originalFileName = basename($_FILES["fileInput"]["name"]);
    $fileType = $_FILES["fileInput"]["type"];
    $tmpFilePath = $_FILES["fileInput"]["tmp_name"];
    $fileSizeBytes = $_FILES["fileInput"]["size"];

    // Convert file size to different units
    $fileSizeBits = $fileSizeBytes * 8;
    $fileSizeKb = $fileSizeBytes / 1024;
    $fileSizeMb = $fileSizeKb / 1024;

    // Additional information
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $uploadDate = date("Y-m-d H:i:s");

    // Set maximum file size (in bytes)
    $maxFileSize = 4 * 1024 * 1024 * 1024; // 4096MB (4GB)

    // Generate a unique filename
    $uniqueFilename = uniqid('uploaded_') . '_' . $originalFileName;

    // Move the uploaded file to a desired location
    $uploadDirectory = "uploads/";
    $newFilePath = $uploadDirectory . $uniqueFilename;

    // Check file size
    if ($fileSizeBytes > $maxFileSize) {
        $errorMessage = "Error: File size exceeds the allowed limit.";
    } elseif (move_uploaded_file($tmpFilePath, $newFilePath)) {
        $successMessage = "File uploaded successfully!";
    } else {
        $errorMessage = "Error uploading file.";
    }
} else {
    $errorMessage = "Please choose a file!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Example</title>
    <style>
        
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.container {
    text-align: center;
}

table {
    border-collapse: collapse;
    width: 100%;
    max-width: 600px;
    margin: auto;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

.success-message, .error-message {
    font-weight: bold;
    margin-bottom: 15px;
}

form {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
}

input[type="file"] {
    display: block;
    margin-bottom: 20px;
    width: 100%;
}

input[type="submit"] {
    background-color: #4caf50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

@media screen and (max-width: 768px) {
    form {
        padding: 15px; /* Adjust padding for smaller screens */
    }

    table {
        max-width: 100%; /* Ensure the table takes up the full width on smaller screens */
    }

    input[type="submit"] {
        width: auto; /* Allow button to take up the necessary width */
    }
}
</style>
</head>
<body>
    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <label for="fileInput">Choose a file:</label>
            <input type="file" name="fileInput" id="fileInput">
            <input type="submit" value="Upload File">
        </form>

        <?php
        if (isset($successMessage)) {
            echo "<div class='success-message'>$successMessage</div>";
            echo "<div id='table-container'>";
            echo "<table>";
            echo "<tr><th>Information</th><th>Details</th></tr>";
            echo "<tr><td>Original File Name</td><td>$originalFileName</td></tr>";
            echo "<tr><td>File Type</td><td>$fileType</td></tr>";
            echo "<tr><td>File Size</td><td>$fileSizeBytes bytes<br>$fileSizeBits bits<br>" .
                round($fileSizeKb, 2) . " KB<br>" . round($fileSizeMb, 2) . " MB</td></tr>";
            echo "<tr><td>File Extension</td><td>$fileExtension</td></tr>";
            echo "<tr><td>Upload Date</td><td>$uploadDate</td></tr>";
            echo "<tr><td>Direct Link</td><td><a href='$newFilePath' target='_blank'>$uniqueFilename</a></td></tr>";
            echo "</table>";
            echo "</div>";
        } elseif (isset($errorMessage)) {
            echo "<div class='error-message'>$errorMessage</div>";
        }
        ?>
    </div>
</body>
</html>
