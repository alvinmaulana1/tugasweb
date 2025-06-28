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
    <title>Data Dosen - Akademik XYZ</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        .back-btn {
            display: inline-block; margin-bottom: 20px;
            padding: 10px 15px; background-color: #2ecc71;
            color: white; text-decoration: none; border-radius: 5px; font-weight: bold;
        }
        form {
            margin-bottom: 20px; background: #fff;
            padding: 20px; border-radius: 5px;
        }
        input {
            padding: 8px; margin: 5px; width: 200px;
        }
        #searchInput {
            width: 300px; padding: 8px; margin-bottom: 15px;
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

<h2>Form Tambah Dosen</h2>

<div class="notif" id="notif"></div>

<form id="formDosen">
    <input type="text" name="no_telp" placeholder="No. Telepon" required>
    <input type="text" name="nama" placeholder="Nama Dosen" required>
    <input type="text" name="matakuliah" placeholder="Mata Kuliah Ajar">
    <button type="submit">Tambah</button>
</form>

<h2>Daftar Dosen</h2>

<label for="searchInput">🔍 Cari Dosen (Nama / No. Telp):</label><br>
<input type="text" id="searchInput" placeholder="Ketik untuk mencari...">

<table id="tabelDosen">
    <thead>
        <tr>
            <th>No</th>
            <th>No. Telepon</th>
            <th>Nama</th>
            <th>Mata Kuliah Ajar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $result = mysqli_query($koneksi, "SELECT * FROM dosen");
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr id='row{$row['id_dosen']}'>
            <td class='no-urut'>{$no}</td>
            <td>{$row['no_telp']}</td>
            <td>{$row['nama']}</td>
            <td>{$row['matakuliah_ajar']}</td>
            <td>
                <button class='btn-edit' onclick='editDosen({$row['id_dosen']})'>Edit</button>
                <button class='btn-danger' onclick='hapusDosen({$row['id_dosen']})'>Hapus</button>
            </td>
        </tr>";
        $no++;
    }
    ?>
    </tbody>
</table>

<!-- Modal Edit -->
<div class="modal" id="modalEdit">
    <div class="modal-content">
        <h3>Edit Dosen</h3>
        <form id="formEdit">
            <input type="hidden" name="id">
            <input type="text" name="no_telp" placeholder="No. Telepon" required><br>
            <input type="text" name="nama" placeholder="Nama Dosen" required><br>
            <input type="text" name="matakuliah" placeholder="Mata Kuliah Ajar"><br>
            <button type="submit">Simpan Perubahan</button>
            <button type="button" onclick="$('#modalEdit').hide()">Batal</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){
    updateNomorUrut();

    $("#formDosen").on("submit", function(e){
        e.preventDefault();
        $.post("dosen_proses.php", $(this).serialize() + "&aksi=tambah", function(res){
            if(res.startsWith("<tr")){
                $("#tabelDosen tbody").append(res);
                $("#notif").text("Data dosen berhasil ditambahkan!").fadeIn().delay(2000).fadeOut();
                $("#formDosen")[0].reset();
                updateNomorUrut();
            } else {
                alert("Gagal menambahkan: " + res);
            }
        });
    });

    $("#formEdit").on("submit", function(e){
        e.preventDefault();
        $.post("dosen_proses.php", $(this).serialize() + "&aksi=update", function(res){
            if(res === "sukses") {
                $("#modalEdit").hide();
                location.reload();
            } else {
                alert("Gagal update data dosen.");
            }
        });
    });

    // 🔍 Live Search Filter
    $("#searchInput").on("keyup", function(){
        let value = $(this).val().toLowerCase();
        $("#tabelDosen tbody tr").filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
        updateNomorUrut();
    });
});

function hapusDosen(id) {
    if (confirm("Yakin ingin menghapus data ini?")) {
        $.post("dosen_proses.php", {hapus: id}, function(res) {
            if (res === "sukses") {
                $("#row" + id).fadeOut(function(){
                    $(this).remove();
                    updateNomorUrut();
                });
            } else {
                alert("Gagal menghapus!");
            }
        });
    }
}

function editDosen(id) {
    $.post("dosen_proses.php", {get: id}, function(res){
        var data = JSON.parse(res);
        $("#formEdit [name=id]").val(data.id_dosen);
        $("#formEdit [name=no_telp]").val(data.no_telp);
        $("#formEdit [name=nama]").val(data.nama);
        $("#formEdit [name=matakuliah]").val(data.matakuliah_ajar);
        $("#modalEdit").fadeIn();
    });
}

function updateNomorUrut() {
    $("#tabelDosen tbody tr:visible").each(function(index){
        $(this).find("td.no-urut").text(index + 1);
    });
}
</script>

</body>
</html>
