<?php
include 'koneksi.php';

if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $no_telp = $_POST['no_telp'];
    $nama = $_POST['nama'];
    $matakuliah = $_POST['matakuliah'];

    mysqli_query($koneksi, "INSERT INTO dosen (no_telp, nama, matakuliah_ajar) 
                            VALUES ('$no_telp', '$nama', '$matakuliah')");

    $id = mysqli_insert_id($koneksi);

    echo "<tr id='row{$id}'>
        <td class='no-urut'></td>
        <td>$no_telp</td>
        <td>$nama</td>
        <td>$matakuliah</td>
        <td>
            <button class='btn-edit' onclick='editDosen($id)'>Edit</button>
            <button class='btn-danger' onclick='hapusDosen($id)'>Hapus</button>
        </td>
    </tr>";
    exit;
}

if (isset($_POST['hapus'])) {
    $id = $_POST['hapus'];
    $hapus = mysqli_query($koneksi, "DELETE FROM dosen WHERE id_dosen = $id");
    echo $hapus ? "sukses" : "gagal";
    exit;
}

if (isset($_POST['get'])) {
    $id = $_POST['get'];
    $data = mysqli_query($koneksi, "SELECT * FROM dosen WHERE id_dosen = $id");
    echo json_encode(mysqli_fetch_assoc($data));
    exit;
}

if (isset($_POST['aksi']) && $_POST['aksi'] == 'update') {
    $id = $_POST['id'];
    $no_telp = $_POST['no_telp'];
    $nama = $_POST['nama'];
    $matakuliah = $_POST['matakuliah'];

    $update = mysqli_query($koneksi, "UPDATE dosen SET 
        no_telp='$no_telp', nama='$nama', matakuliah_ajar='$matakuliah' 
        WHERE id_dosen = $id");
    echo $update ? "sukses" : "gagal";
    exit;
}
