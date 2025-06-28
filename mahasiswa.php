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
    <title>Data Mahasiswa - Akademik XYZ</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        .back-btn {
            display: inline-block; margin-bottom: 20px;
            padding: 10px 15px; background-color: #2ecc71;
            color: white; text-decoration: none; border-radius: 5px; font-weight: bold;
        }
        form {
            margin-bottom: 20px;
            background: #fff; padding: 20px; border-radius: 5px;
        }
        input, select {
            padding: 8px; margin: 5px; width: 200px;
        }
        #searchInput {
            width: 300px; padding: 8px; margin-bottom: 15px; margin-top: 5px;
        }
        button {
            padding: 8px 12px; background-color: #2980b9;
            color: white; border: none; border-radius: 4px; cursor: pointer;
        }
        table {
            width: 100%; border-collapse: collapse; background: #fff;
        }
        th, td {
            border: 1px solid #ddd; padding: 8px; text-align: center;
        }
        th { background: #34495e; color: white; }
        .btn-danger, .btn-edit {
            color: white; border: none; padding: 6px 10px; border-radius: 3px; cursor: pointer;
        }
        .btn-danger { background: crimson; }
        .btn-edit { background: orange; }
        .notif {
            padding: 10px; background: #2ecc71; color: white;
            margin-bottom: 15px; display: none; border-radius: 5px;
        }
        .modal {
            display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); justify-content: center; align-items: center;
        }
        .modal-content {
            background: white; padding: 20px; width: 400px; border-radius: 10px;
        }
    </style>
</head>
<body>

<a href="dashboard.php" class="back-btn">← Kembali ke Dashboard</a>

<h2>Form Tambah Mahasiswa</h2>

<div class="notif" id="notif"></div>

<form id="formMahasiswa">
    <input type="text" name="npm" placeholder="NPM" required>
    <input type="text" name="nama" placeholder="Nama" required>
    <input type="text" name="jurusan" placeholder="Jurusan">
    <select name="semester" required>
        <option value="">Semester</option>
        <?php for($i = 1; $i <= 8; $i++) echo "<option value='$i'>$i</option>"; ?>
    </select>
    <button type="submit">Tambah</button>
</form>

<h2>Daftar Mahasiswa</h2>

<label for="searchInput">🔍 Cari Mahasiswa (NPM / Nama):</label><br>
<input type="text" id="searchInput" placeholder="Ketik untuk mencari...">

<table id="tabelMahasiswa">
    <thead>
        <tr>
            <th>No</th>
            <th>NPM</th>
            <th>Nama</th>
            <th>Jurusan</th>
            <th>Semester</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $result = mysqli_query($koneksi, "SELECT * FROM mahasiswa");
    $no = 1;
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr id='row{$row['id_mahasiswa']}'>
            <td class='no-urut'>{$no}</td>
            <td>{$row['npm']}</td>
            <td>{$row['nama']}</td>
            <td>{$row['jurusan']}</td>
            <td>{$row['semester']}</td>
            <td>
                <button class='btn-edit' onclick='editMahasiswa({$row['id_mahasiswa']})'>Edit</button>
                <button class='btn-danger' onclick='hapusMahasiswa({$row['id_mahasiswa']})'>Hapus</button>
            </td>
        </tr>";
        $no++;
    }
    ?>
    </tbody>
</table>

<!-- Modal Edit (sama seperti versi sebelumnya) -->
<div class="modal" id="modalEdit">
    <div class="modal-content">
        <h3>Edit Mahasiswa</h3>
        <form id="formEdit">
            <input type="hidden" name="id">
            <input type="text" name="npm" placeholder="NPM" required><br>
            <input type="text" name="nama" placeholder="Nama" required><br>
            <input type="text" name="jurusan" placeholder="Jurusan"><br>
            <select name="semester" required>
                <option value="">Semester</option>
                <?php for($i = 1; $i <= 8; $i++) echo "<option value='$i'>$i</option>"; ?>
            </select><br>
            <button type="submit">Simpan Perubahan</button>
            <button type="button" onclick="$('#modalEdit').hide()">Batal</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){
    updateNomorUrut();

    $("#formMahasiswa").on("submit", function(e){
        e.preventDefault();
        $.post("mahasiswa_proses.php", $(this).serialize() + "&aksi=tambah", function(res){
            if(res.startsWith("<tr")){
                $("#tabelMahasiswa tbody").append(res);
                $("#notif").text("Data berhasil ditambahkan").fadeIn().delay(2000).fadeOut();
                $("#formMahasiswa")[0].reset();
                updateNomorUrut();
            } else {
                alert("Gagal: " + res);
            }
        });
    });

    $("#formEdit").on("submit", function(e){
        e.preventDefault();
        $.post("mahasiswa_proses.php", $(this).serialize() + "&aksi=update", function(res){
            if(res === "sukses") {
                $("#modalEdit").hide();
                location.reload();
            } else {
                alert("Gagal update");
            }
        });
    });

    // Live Search Filter
    $("#searchInput").on("keyup", function(){
        let value = $(this).val().toLowerCase();
        $("#tabelMahasiswa tbody tr").filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});

function hapusMahasiswa(id){
    if(confirm("Hapus data ini?")){
        $.post("mahasiswa_proses.php", {hapus: id}, function(res){
            if(res === "sukses"){
                $("#row"+id).fadeOut(function(){
                    $(this).remove();
                    updateNomorUrut();
                });
            } else {
                alert("Gagal menghapus");
            }
        });
    }
}

function editMahasiswa(id){
    $.post("mahasiswa_proses.php", {get: id}, function(res){
        var data = JSON.parse(res);
        $("#formEdit [name=id]").val(data.id_mahasiswa);
        $("#formEdit [name=npm]").val(data.npm);
        $("#formEdit [name=nama]").val(data.nama);
        $("#formEdit [name=jurusan]").val(data.jurusan);
        $("#formEdit [name=semester]").val(data.semester);
        $("#modalEdit").fadeIn();
    });
}

function updateNomorUrut() {
    $("#tabelMahasiswa tbody tr:visible").each(function(index){
        $(this).find("td.no-urut").text(index + 1);
    });
}
</script>

</body>
</html>
