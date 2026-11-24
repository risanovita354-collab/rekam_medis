<?php
include 'koneksi.php';
include 'layout.php';

// Simpan Tambah / Edit
if (isset($_POST['simpan'])) {
    $id   = $_POST['id_dokter'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_dokter']);
    $spes = mysqli_real_escape_string($koneksi, $_POST['spesialisasi']);

    try {
        if ($id == "") {
            $q = mysqli_query($koneksi, "INSERT INTO dokter (nama_dokter, spesialisasi) VALUES ('$nama','$spes')");
            $msg = "Data dokter berhasil ditambahkan!";
        } else {
            $q = mysqli_query($koneksi, "UPDATE dokter SET nama_dokter='$nama', spesialisasi='$spes' WHERE id_dokter='$id'");
            $msg = "Data dokter berhasil diperbarui!";
        }
        if (!$q) throw new Exception(mysqli_error($koneksi));
        echo "<script>alert('$msg'); window.location='dokter.php';</script>";
        exit;
    } catch (Throwable $e) {
        echo "<script>alert('Gagal menyimpan data dokter!'); window.location='dokter.php';</script>";
        exit;
    }
}

// Hapus dengan Try-Catch agar tidak layar putih saat ditolak Foreign Key
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    try {
        $q = mysqli_query($koneksi, "DELETE FROM dokter WHERE id_dokter='$id'");
        if (!$q) throw new Exception(mysqli_error($koneksi));
        echo "<script>alert('Data dokter berhasil dihapus!'); window.location='dokter.php';</script>";
        exit;
    } catch (Throwable $e) {
        echo "<script>alert('Gagal menghapus! Dokter ini masih tercatat pada data pemeriksaan (terikat relasi Foreign Key).'); window.location='dokter.php';</script>";
        exit;
    }
}

$aksi = $_GET['aksi'] ?? 'list';

if ($aksi == 'tambah' || $aksi == 'edit'):
    $edit = ['id_dokter'=>'', 'nama_dokter'=>'', 'spesialisasi'=>''];
    $judul_form = ($aksi == 'edit') ? "Edit Dokter" : "Tambah Dokter";
    if ($aksi == 'edit' && isset($_GET['id'])) {
        $id = mysqli_real_escape_string($koneksi, $_GET['id']);
        $edit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM dokter WHERE id_dokter='$id'"));
    }
    header_layout($judul_form, "Data Dokter &nbsp;>&nbsp; $judul_form", "dokter");
?>
    <div class="card-box">
        <form method="POST">
            <input type="hidden" name="id_dokter" value="<?= $edit['id_dokter'] ?>">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nama Dokter</label>
                    <input type="text" name="nama_dokter" value="<?= htmlspecialchars($edit['nama_dokter']) ?>" placeholder="Contoh: dr. Andi Pratama" required>
                </div>
                <div class="form-group">
                    <label>Spesialisasi</label>
                    <input type="text" name="spesialisasi" value="<?= htmlspecialchars($edit['spesialisasi']) ?>" placeholder="Contoh: Penyakit Dalam, Anak, Umum" required>
                </div>
            </div>
            <div class="form-actions">
                <a href="dokter.php" class="btn btn-secondary">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
<?php
else:
    header_layout("Data Dokter", "Data Dokter", "dokter");
    $cari = $_GET['cari'] ?? '';
    $cari_esc = mysqli_real_escape_string($koneksi, $cari);
    $where = $cari ? "WHERE nama_dokter LIKE '%$cari_esc%' OR spesialisasi LIKE '%$cari_esc%'" : "";
?>
    <div class="card-box">
        <div class="card-header">
            <form method="GET" class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari nama dokter atau spesialisasi...">
            </form>
            <a href="dokter.php?aksi=tambah" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Dokter</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th><th>ID Dokter</th><th>Nama Dokter</th><th>Spesialisasi</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $q = mysqli_query($koneksi, "SELECT * FROM dokter $where ORDER BY id_dokter ASC");
                while ($row = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                    <td><?= $no ?></td>
                    <td>D<?= str_pad($no, 3, '0', STR_PAD_LEFT) ?></td>
                    <td><?= htmlspecialchars($row['nama_dokter']) ?></td>
                    <td><?= htmlspecialchars($row['spesialisasi']) ?></td>
                    <td>
                        <div class="action-group">
                            <a href="dokter.php?aksi=edit&id=<?= $row['id_dokter'] ?>" class="act-btn act-edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="dokter.php?hapus=<?= $row['id_dokter'] ?>" class="act-btn act-del" title="Hapus" onclick="return confirm('Yakin hapus data dokter ini?')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php $no++; endwhile; ?>
            </tbody>
        </table>
    </div>
<?php endif; footer_layout(); ?>