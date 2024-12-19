<?php
session_start();
include 'db.php';

// Cek apakah pengguna sudah login dan memiliki peran yang benar
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'pimpinan')) {
    header("Location: login.php");
    exit();
}

// Ambil data pendonor dari database
$stmt = $pdo->prepare("SELECT * FROM donors");
$stmt->execute();
$donors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Daftar Pendonor</h2>
    <table>
        <tr>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Tanggal Lahir</th>
            <th>No Telepon</th>
            <th>Email</th>
            <th>Golongan Darah</th>
            <th>Status Kesehatan</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($donors as $donor): ?>
            <tr>
                <td><?php echo htmlspecialchars($donor['name']); ?></td>
                <td><?php echo htmlspecialchars($donor['address']); ?></td>
                <td><?php echo htmlspecialchars($donor['birth_date']); ?></td>
                <td><?php echo htmlspecialchars($donor['phone_number']); ?></td>
                <td><?php echo htmlspecialchars($donor['email']); ?></td>
                <td><?php echo htmlspecialchars($donor['blood_type']); ?></td>
                <td><?php echo htmlspecialchars($donor['health_history']); ?></td>
                <td>
                    <?php if (!empty($donor['photo'])): ?>
                        <img src="<?php echo htmlspecialchars($donor['photo']); ?>" alt="Foto Pendonor" style="width: 100px; height: auto;">
                    <?php else: ?>
                        Tidak ada foto
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_donor.php?id=<?php echo $donor['id']; ?>">Edit</a>
                    <a href="delete_donor.php?id=<?php echo $donor['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pendonor ini?');">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="logout.php">Logout</a>

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