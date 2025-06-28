<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Tambah data nilai
if (isset($_POST['tambah'])) {
    $id_mahasiswa = $_POST['mahasiswa'];
    $id_matakuliah = $_POST['matakuliah'];
    $nilai_angka = $_POST['nilai_angka'];

    // Konversi nilai huruf
    if ($nilai_angka >= 85) $nilai_huruf = 'A';
    elseif ($nilai_angka >= 75) $nilai_huruf = 'B';
    elseif ($nilai_angka >= 65) $nilai_huruf = 'C';
    elseif ($nilai_angka >= 50) $nilai_huruf = 'D';
    else $nilai_huruf = 'E';

    $semester = $_POST['semester'];

    $query = "INSERT INTO nilai (id_mahasiswa, id_matakuliah, nilai_angka, nilai_huruf, semester) 
              VALUES ('$id_mahasiswa', '$id_matakuliah', '$nilai_angka', '$nilai_huruf', '$semester')";
    mysqli_query($koneksi, $query);
    header("Location: nilai.php");
}

// Ambil data untuk dropdown
$mahasiswa = mysqli_query($koneksi, "SELECT * FROM mahasiswa");
$matakuliah = mysqli_query($koneksi, "SELECT * FROM matakuliah");

// Ambil semua data nilai
$nilai = mysqli_query($koneksi, "
    SELECT nilai.*, mahasiswa.nama AS nama_mahasiswa, matakuliah.nama_matakuliah 
    FROM nilai 
    JOIN mahasiswa ON nilai.id_mahasiswa = mahasiswa.id_mahasiswa 
    JOIN matakuliah ON nilai.id_matakuliah = matakuliah.id_matakuliah
");

// Hapus nilai
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM nilai WHERE id_nilai = $id");
    header("Location: nilai.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nilai Mahasiswa - Akademik XYZ</title>
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

<h2>Form Input Nilai Mahasiswa</h2>
<form method="POST">
    <select name="mahasiswa" required>
        <option value="">Pilih Mahasiswa</option>
        <?php while ($m = mysqli_fetch_assoc($mahasiswa)) {
            echo "<option value='{$m['id_mahasiswa']}'>{$m['npm']} - {$m['nama']}</option>";
        } ?>
    </select>

    <select name="matakuliah" required>
        <option value="">Pilih Mata Kuliah</option>
        <?php while ($mk = mysqli_fetch_assoc($matakuliah)) {
            echo "<option value='{$mk['id_matakuliah']}'>{$mk['kode_matakuliah']} - {$mk['nama_matakuliah']}</option>";
        } ?>
    </select>

    <input type="number" name="nilai_angka" placeholder="Nilai Angka" required>
    <select name="semester" required>
        <option value="">Semester</option>
        <?php for($i = 1; $i <= 8; $i++) echo "<option value='$i'>$i</option>"; ?>
    </select>
    <button type="submit" name="tambah">Simpan Nilai</button>
</form>

<h2>Daftar Nilai Mahasiswa</h2>
<table>
    <tr>
        <th>No</th>
        <th>Mahasiswa</th>
        <th>Mata Kuliah</th>
        <th>Semester</th>
        <th>Nilai Angka</th>
        <th>Nilai Huruf</th>
        <th>Aksi</th>
    </tr>
    <?php $no = 1; while($row = mysqli_fetch_assoc($nilai)) { ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_mahasiswa'] ?></td>
        <td><?= $row['nama_matakuliah'] ?></td>
        <td><?= $row['semester'] ?></td>
        <td><?= $row['nilai_angka'] ?></td>
        <td><?= $row['nilai_huruf'] ?></td>
        <td>
            <a href="nilai.php?hapus=<?= $row['id_nilai'] ?>" 
               onclick="return confirm('Hapus data ini?')" 
               class="btn-danger">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
