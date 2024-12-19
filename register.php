<?php
session_start();
include 'db.php'; // Koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $birth_date = $_POST['birth_date'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $blood_type = $_POST['blood_type'];
    $health_history = $_POST['health_history'];

    // Upload foto
    $photo = $_FILES['photo']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo);

    // Cek apakah file foto berhasil di-upload
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
        // Simpan data pendonor ke database
        $stmt = $pdo->prepare("INSERT INTO donors (name, address, birth_date, phone_number, email, photo, blood_type, health_history) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $address, $birth_date, $phone_number, $email, $target_file, $blood_type, $health_history])) {
            $_SESSION['success'] = "Pendaftaran berhasil!";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Pendaftaran gagal!";
        }
    } else {
        $_SESSION['error'] = "Gagal meng-upload foto!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Donor Darah</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link ke file CSS eksternal -->
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
    <h2>Pendaftaran Donor Darah</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Nama:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="address">Alamat:</label>
        <textarea id="address" name="address" required></textarea>
        
        <label for="birth_date">Tanggal Lahir:</label>
        <input type="date" id="birth_date" name="birth_date" required>
        
        <label for="phone_number">Nomor Telepon:</label>
        <input type="text" id="phone_number" name="phone_number" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="photo">Foto:</label>
        <input type="file" id="photo" name="photo" accept="image/*" required>
        
        <label for="blood_type">Golongan Darah:</label>
        <select id="blood_type" name="blood_type" required>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="AB">AB</option>
            <option value="O">O</option>
        </select>
        
        <label for="health_history">Riwayat Kesehatan:</label>
        <textarea id="health_history" name="health_history" required></textarea>
        
        <button type="submit">Daftar</button>
    </form>

    <?php
    // Menampilkan pesan sukses atau error
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