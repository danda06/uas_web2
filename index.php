<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Pendaftaran Donor Darah</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Sistem Informasi Pendaftaran Donor Darah</h1>
        <nav>
            <a href="register.php">Daftar Sebagai Pendonor</a>
            <a href="login.php">Login</a>
            <a href="register_admin.php">Daftar Sebagai Admin/Pimpinan</a> <!-- Tautan baru untuk pendaftaran admin/pimpinan -->
        </nav>
    </header>
    <main>
        <h2>Selamat Datang!</h2>
        <p>Selamat datang di sistem informasi pendaftaran donor darah. Kami mengajak Anda untuk berpartisipasi dalam menyelamatkan nyawa dengan mendonorkan darah Anda.</p>
        <p>Silakan pilih salah satu opsi di atas untuk mendaftar sebagai pendonor atau untuk login jika Anda sudah memiliki akun.</p>
        
        <?php
        if (isset($_SESSION['success'])) {
            echo "<p class='success'>".$_SESSION['success']."</p>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        }
        ?>
    </main>
</body>
</html>