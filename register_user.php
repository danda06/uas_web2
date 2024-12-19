<?php
session_start();
include 'db.php';

// Cek apakah pengguna sudah mengirimkan formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password
    $role = $_POST['role']; // Ambil role dari formulir

    // Simpan ke database
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $password, $role])) {
        $_SESSION['success'] = "Pengguna berhasil didaftarkan!";
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat mendaftar pengguna.";
    }

    header("Location: register_user.php"); // Redirect kembali ke halaman pendaftaran
    exit();
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
    <h2>Pendaftaran Pengguna</h2>
    <form action="" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="admin">Admin</option>
            <option value="pimpinan">Pimpinan</option>
        </select>
        
        <button type="submit">Daftar</button>
    </form>
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
</body>
</html>