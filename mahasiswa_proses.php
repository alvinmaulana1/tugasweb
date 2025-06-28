<?php
include 'koneksi.php';

if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $npm = $_POST['npm'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $semester = $_POST['semester'];

    mysqli_query($koneksi, "INSERT INTO mahasiswa (npm, nama, jurusan, semester) 
                            VALUES ('$npm', '$nama', '$jurusan', '$semester')");
    $id = mysqli_insert_id($koneksi);

    echo "<tr id='row{$id}'>
        <td class='no-urut'></td>
        <td>$npm</td>
        <td>$nama</td>
        <td>$jurusan</td>
        <td>$semester</td>
        <td>
            <button class='btn-edit' onclick='editMahasiswa($id)'>Edit</button>
            <button class='btn-danger' onclick='hapusMahasiswa($id)'>Hapus</button>
        </td>
    </tr>";
    exit;
}

if (isset($_POST['hapus'])) {
    $id = $_POST['hapus'];
    $hapus = mysqli_query($koneksi, "DELETE FROM mahasiswa WHERE id_mahasiswa = $id");
    echo $hapus ? "sukses" : "gagal";
    exit;
}

if (isset($_POST['get'])) {
    $id = $_POST['get'];
    $data = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE id_mahasiswa = $id");
    echo json_encode(mysqli_fetch_assoc($data));
    exit;
}

if (isset($_POST['aksi']) && $_POST['aksi'] == 'update') {
    $id = $_POST['id'];
    $npm = $_POST['npm'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $semester = $_POST['semester'];

    $update = mysqli_query($koneksi, "UPDATE mahasiswa SET 
        npm='$npm', nama='$nama', jurusan='$jurusan', semester='$semester' 
        WHERE id_mahasiswa = $id");
    echo $update ? "sukses" : "gagal";
    exit;
}
