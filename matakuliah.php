<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Tambah data
if (isset($_POST['tambah'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $sks = $_POST['sks'];
    $semester = $_POST['semester'];

    $query = "INSERT INTO matakuliah (kode_matakuliah, nama_matakuliah, sks, semester) 
              VALUES ('$kode', '$nama', '$sks', '$semester')";
    mysqli_query($koneksi, $query);
    header("Location: matakuliah.php");
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM matakuliah WHERE id_matakuliah = $id");
    header("Location: matakuliah.php");
}

// Ambil semua data
$matakuliah = mysqli_query($koneksi, "SELECT * FROM matakuliah");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Mata Kuliah - Akademik XYZ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f4f4f4;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .back-btn:hover {
            background-color: #27ae60;
        }
        form {
            margin-bottom: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
        }
        input, select {
            padding: 8px;
            margin: 5px;
            width: 200px;
        }
        button {
            padding: 8px 12px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #34495e;
            color: white;
        }
        .btn-danger {
            color: white;
            background: crimson;
            border: none;
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 3px;
        }
        .btn-danger:hover {
            background: darkred;
        }
    </style>
</head>
<body>

<a href="dashboard.php" class="back-btn">← Kembali ke Dashboard</a>

<h2>Form Tambah Mata Kuliah</h2>
<form method="POST">
    <input type="text" name="kode" placeholder="Kode MK" required>
    <input type="text" name="nama" placeholder="Nama Mata Kuliah" required>
    <input type="number" name="sks" placeholder="SKS" required>
    <select name="semester" required>
        <option value="">Semester</option>
        <?php for ($i = 1; $i <= 8; $i++) echo "<option value='$i'>$i</option>"; ?>
    </select>
    <button type="submit" name="tambah">Tambah</button>
</form>

<h2>Daftar Mata Kuliah</h2>
<table>
    <tr>
        <th>No</th>
        <th>Kode</th>
        <th>Nama</th>
        <th>SKS</th>
        <th>Semester</th>
        <th>Aksi</th>
    </tr>
    <?php $no = 1; while ($row = mysqli_fetch_assoc($matakuliah)) { ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['kode_matakuliah'] ?></td>
        <td><?= $row['nama_matakuliah'] ?></td>
        <td><?= $row['sks'] ?></td>
        <td><?= $row['semester'] ?></td>
        <td>
            <a href="matakuliah.php?hapus=<?= $row['id_matakuliah'] ?>" 
               onclick="return confirm('Hapus data ini?')" 
               class="btn-danger">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
