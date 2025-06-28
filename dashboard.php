<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Sistem Akademik XYZ</title>
    <style>
        * {
            margin: 0; padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ecf0f1;
        }

        header {
            background-color: #2c3e50;
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        header p {
            margin: 4px 0;
            color: #bdc3c7;
        }

        header p strong {
            color: #ecf0f1;
        }

        .container {
            display: flex;
        }

        nav {
            width: 220px;
            background-color: #34495e;
            height: 100vh;
            padding: 20px;
        }

        nav h3 {
            color: white;
            margin-bottom: 20px;
        }

        nav a {
            display: block;
            color: #ecf0f1;
            background: #2c3e50;
            padding: 10px 15px;
            margin-bottom: 10px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        nav a:hover {
            background-color: #1abc9c;
            color: white;
            transform: translateX(5px);
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            flex: 1 1 250px;
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .card p {
            font-size: 20px;
            color: #27ae60;
        }

        @media screen and (max-width: 768px) {
            nav {
                display: none;
            }

            .container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>🎓 Sistem Informasi Akademik XYZ</h1>
    <p>Sistem Manajemen Data Akademik Terintegrasi</p>
    <p><strong>Dibuat oleh:</strong> Alvin Maulanasani - NPM: 202243500574</p>
</header>

<div class="container">
    <nav>
        <h3>MENU</h3>
        <a href="dashboard.php">Dashboard</a>
        <a href="mahasiswa.php">Data Mahasiswa</a>
        <a href="dosen.php">Data Dosen</a>
        <a href="matakuliah.php">Mata Kuliah</a>
        <a href="nilai.php">Nilai Mahasiswa</a>
        <a href="lihat_nilai.php">Lihat Nilai Mahasiswa</a> <!-- ✅ MENU TAMBAHAN -->
        <a href="logout.php" style="background-color: crimson;">Logout</a>
    </nav>

    <div class="main-content">
        <h2>Selamat Datang, <?= $_SESSION['admin']; ?>!</h2>
        <p style="margin-bottom: 30px;">Berikut ini ringkasan data akademik:</p>

        <div class="card-container">
            <div class="card">
                <h3>Total Mahasiswa</h3>
                <?php
                $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM mahasiswa");
                $data = mysqli_fetch_assoc($result);
                echo "<p>{$data['total']} Mahasiswa</p>";
                ?>
            </div>

            <div class="card">
                <h3>Total Dosen</h3>
                <?php
                $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM dosen");
                $data = mysqli_fetch_assoc($result);
                echo "<p>{$data['total']} Dosen</p>";
                ?>
            </div>

            <div class="card">
                <h3>Total Mata Kuliah</h3>
                <?php
                $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM matakuliah");
                $data = mysqli_fetch_assoc($result);
                echo "<p>{$data['total']} Mata Kuliah</p>";
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
