<?php
session_start();
include 'db.php'; // Pastikan Anda sudah membuat koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role']; // 'admin' atau 'pimpinan'

    // Cek apakah username sudah ada
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $existingUser  = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser ) {
        $_SESSION['error'] = "Username sudah terdaftar. Silakan pilih username lain.";
    } else {
        // Menyimpan pengguna baru ke database
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $email, $role]);

        $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
        header("Location: login.php"); // Redirect ke halaman login
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Pengguna</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Pendaftaran Pengguna</h1>
    </header>
    <main>
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="pimpinan">Pimpinan</option>
            </select>
            
            <button type="submit">Daftar</button>
        </form>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo "<p class='success'>".$_SESSION['success']."</p>";
            unset($_SESSION['success']);
        }
        ?>
    </main>
</body>
</html>