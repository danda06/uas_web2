<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'pimpinan')) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM donors WHERE id = ?");
    $stmt->execute([$id]);
    $donor = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $birth_date = $_POST['birth_date'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $blood_type = $_POST['blood_type'];
    $health_history = $_POST['health_history'];

    $stmt = $pdo->prepare("UPDATE donors SET name = ?, address = ?, birth_date = ?, phone_number = ?, email = ?, blood_type = ?, health_history = ? WHERE id = ?");
    if ($stmt->execute([$name, $address, $birth_date, $phone_number, $email, $blood_type, $health_history, $id])) {
        $_SESSION['success'] = "Data pendonor berhasil diperbarui!";
        // Redirect berdasarkan role
        if ($_SESSION['role'] === 'admin') {
            header("Location: admin.php");
        } elseif ($_SESSION['role'] === 'pimpinan') {
            header("Location: report.php");
        }
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui data pendonor!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pendonor</title>
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
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Edit Pendonor</h2>
    <form action="" method="POST">
        <label for="name">Nama:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($donor['name']); ?>" required>
        
        <label for="address">Alamat:</label>
        <textarea id="address" name="address" required><?php echo htmlspecialchars($donor['address']); ?></textarea>
        
        <label for="birth_date">Tanggal Lahir:</label>
        <input type="date" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($donor['birth_date']); ?>" required>
        
        <label for="phone_number">Nomor Telepon:</label>
        <label for="phone_number">Nomor Telepon:</label>
        <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($donor['phone_number']); ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($donor['email']); ?>" required>
        
        <label for="blood_type">Golongan Darah:</label>
        <select id="blood_type" name="blood_type" required>
            <option value="A" <?php echo $donor['blood_type'] == 'A' ? 'selected' : ''; ?>>A</option>
            <option value="B" <?php echo $donor['blood_type'] == 'B' ? 'selected' : ''; ?>>B</option>
            <option value="AB" <?php echo $donor['blood_type'] == 'AB' ? 'selected' : ''; ?>>AB</option>
            <option value="O" <?php echo $donor['blood_type'] == 'O' ? 'selected' : ''; ?>>O</option>
        </select>
        
        <label for="health_history">Riwayat Kesehatan:</label>
        <textarea id="health_history" name="health_history" required><?php echo htmlspecialchars($donor['health_history']); ?></textarea>
        
        <button type="submit">Perbarui</button>
    </form>
    
    <?php
    if (isset($_SESSION['success'])) {
        echo "<p style='color: green;'>".$_SESSION['success']."</p>";
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
    ?>
    
    <div>
        <?php
        if ($_SESSION['role'] === 'admin') {
            echo '<a href="admin.php">Kembali ke Daftar Pendonor</a>';
        }

        if ($_SESSION['role'] === 'pimpinan') {
            echo '<a href="report.php">Kembali ke Laporan Pendonor</a>';
        }
        ?>
    </div>
    
    <div style="margin-top: 20px;">
        <a href="logout.php">Logout</a> <!-- Link untuk logout -->
    </div>
</body>
</html>