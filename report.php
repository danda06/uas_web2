<?php
session_start();
include 'db.php'; // Pastikan Anda sudah membuat koneksi ke database

// Cek apakah pengguna sudah login dan memiliki peran yang benar
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'pimpinan')) {
    header("Location: login.php");
    exit();
}

// Ambil data laporan donor
$stmt = $pdo->prepare("SELECT d.name, d.email, r.donation_date FROM donors d JOIN donation_reports r ON d.id = r.donor_id");
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil data pendonor dari database
$stmt = $pdo->prepare("SELECT * FROM donors");
$stmt->execute();
$donors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengunduh laporan donor dalam format CSV
if (isset($_POST['download_reports'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="laporan_donor.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Nama', 'Email', 'Tanggal Donasi']);
    foreach ($reports as $report) {
        fputcsv($output, $report);
    }
    fclose($output);
    exit();
}

// Mengunduh daftar pendonor dalam format CSV
if (isset($_POST['download_donors'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="daftar_pendonor.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Nama', 'Alamat', 'Tanggal Lahir', 'No Telepon', 'Email', 'Golongan Darah', 'Status Kesehatan']);
    foreach ($donors as $donor) {
        fputcsv($output, [
            $donor['name'],
            $donor['address'],
            $donor['birth_date'],
            $donor['phone_number'],
            $donor['email'],
            $donor['blood_type'],
            $donor['health_history']
        ]);
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Donor dan Daftar Pendonor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f9f9f9;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Laporan Donor</h2>
    <form action="" method="POST">
        <button type="submit" name="download_reports">Unduh Laporan Donor</button>
    </form>
    <table>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Tanggal Donasi</th>
        </tr>
        <?php foreach ($reports as $report): ?>
            <tr>
                <td><?php echo htmlspecialchars($report['name']); ?></td>
                <td><?php echo htmlspecialchars($report['email']); ?></td>
                <td><?php echo htmlspecialchars($report['donation_date']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Daftar Pendonor</h2>
    <form action="" method="POST">
        <button type="submit" name="download_donors">Unduh Daftar Pendonor</button>
    </form>
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