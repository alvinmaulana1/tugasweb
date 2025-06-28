<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// Ambil daftar mahasiswa untuk dropdown
$mahasiswa = mysqli_query($koneksi, "SELECT * FROM mahasiswa");

// Jika ada NPM dikirimkan
$data_nilai = [];
if (isset($_POST['npm']) && $_POST['npm'] !== '') {
    $npm = $_POST['npm'];

    // Ambil ID mahasiswa berdasarkan NPM
    $mhs = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE npm = '$npm'");
    $mahasiswa_data = mysqli_fetch_assoc($mhs);

    if ($mahasiswa_data) {
        $id_mhs = $mahasiswa_data['id_mahasiswa'];

        $data_nilai = mysqli_query($koneksi, "
            SELECT nilai.*, matakuliah.nama_matakuliah 
            FROM nilai 
            JOIN matakuliah ON nilai.id_matakuliah = matakuliah.id_matakuliah 
            WHERE nilai.id_mahasiswa = $id_mhs
        ");
    } else {
        $notfound = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lihat Nilai Mahasiswa - Akademik XYZ</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
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
        form {
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 5px;
        }
        input, select, button {
            padding: 8px;
            margin: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #2c3e50;
            color: white;
        }
        .alert {
            padding: 10px;
            background: #e74c3c;
            color: white;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<a href="dashboard.php" class="back-btn">← Kembali ke Dashboard</a>

<h2>Lihat Nilai Mahasiswa</h2>

<form method="POST">
    <label>Pilih Mahasiswa (NPM)</label><br>
    <select name="npm" required>
        <option value="">-- Pilih Mahasiswa --</option>
        <?php
        while ($m = mysqli_fetch_assoc($mahasiswa)) {
            $selected = (isset($_POST['npm']) && $_POST['npm'] == $m['npm']) ? "selected" : "";
            echo "<option value='{$m['npm']}' $selected>{$m['npm']} - {$m['nama']}</option>";
        }
        ?>
    </select>
    <button type="submit">Lihat Nilai</button>
</form>

<?php if (isset($notfound) && $notfound): ?>
    <div class="alert">NPM tidak ditemukan!</div>
<?php endif; ?>

<?php if (isset($mahasiswa_data)): ?>
    <h3>Nama: <?= $mahasiswa_data['nama'] ?> (<?= $mahasiswa_data['npm'] ?>)</h3>
    <p>Jurusan: <?= $mahasiswa_data['jurusan'] ?> | Semester: <?= $mahasiswa_data['semester'] ?></p>

    <?php if (mysqli_num_rows($data_nilai) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Kuliah</th>
                <th>Semester</th>
                <th>Nilai Angka</th>
                <th>Nilai Huruf</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; while ($n = mysqli_fetch_assoc($data_nilai)) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $n['nama_matakuliah'] ?></td>
                    <td><?= $n['semester'] ?></td>
                    <td><?= $n['nilai_angka'] ?></td>
                    <td><?= $n['nilai_huruf'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php else: ?>
        <p><em>Belum ada nilai.</em></p>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
