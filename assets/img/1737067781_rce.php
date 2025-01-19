<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Uploader</title>
</head>
<body>

    <h2>Upload File</h2>
    
    <!-- Form HTML untuk upload file -->
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="file">Pilih file untuk diunggah:</label>
        <input type="file" name="file" id="file" required>
        <br><br>
        <input type="submit" name="submit" value="Upload">
    </form>

    <?php
    // Cek apakah tombol submit di tekan
    if (isset($_POST['submit'])) {

        // Set direktori tempat penyimpanan file
        $uploadDir = 'uploads/';

        // Jika direktori belum ada, buat direktori
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Ambil nama file dan path tujuan
        $fileName = basename($_FILES['file']['name']);
        $filePath = $uploadDir . $fileName;

        // Cek apakah ada file yang diunggah
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            // Pindahkan file ke direktori tujuan
            if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                echo "File berhasil diunggah ke: " . $filePath;
            } else {
                echo "Gagal mengunggah file!";
            }
        } else {
            echo "Terjadi kesalahan saat mengunggah file.";
        }
    }
    ?>

</body>
</html>
