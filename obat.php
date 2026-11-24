<?php
include 'koneksi.php';
include 'layout.php';

// Simpan Tambah / Edit
if (isset($_POST['simpan'])) {
    $id     = $_POST['id_obat'];
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_obat']);
    $satuan = mysqli_real_escape_string($koneksi, $_POST['satuan']);
    $stok   = mysqli_real_escape_string($koneksi, $_POST['stok']);

    try {
        if ($id == "") {
            $q = mysqli_query($koneksi, "INSERT INTO obat (nama_obat, satuan, stok) VALUES ('$nama','$satuan','$stok')");
            $msg = "Data obat berhasil ditambahkan!";
        } else {
            $q = mysqli_query($koneksi, "UPDATE obat SET nama_obat='$nama', satuan='$satuan', stok='$stok' WHERE id_obat='$id'");
            $msg = "Data obat berhasil diperbarui!";
        }
        if (!$q) throw new Exception(mysqli_error($koneksi));
        echo "<script>alert('$msg'); window.location='obat.php';</script>";
        exit;
    } catch (Throwable $e) {
        echo "<script>alert('Gagal menyimpan data obat!'); window.location='obat.php';</script>";
        exit;
    }
}

// Hapus dengan Try-Catch agar tidak layar putih saat ditolak Foreign Key
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    try {
        $q = mysqli_query($koneksi, "DELETE FROM obat WHERE id_obat='$id'");
        if (!$q) throw new Exception(mysqli_error($koneksi));
        echo "<script>alert('Data obat berhasil dihapus!'); window.location='obat.php';</script>";
        exit;
    } catch (Throwable $e) {
        echo "<script>alert('Gagal menghapus! Obat ini masih digunakan pada data pemeriksaan (terikat relasi Foreign Key).'); window.location='obat.php';</script>";
        exit;
    }
}

$aksi = $_GET['aksi'] ?? 'list';

if ($aksi == 'tambah' || $aksi == 'edit'):
    $edit = ['id_obat'=>'', 'nama_obat'=>'', 'satuan'=>'', 'stok'=>''];
    $judul_form = ($aksi == 'edit') ? "Edit Obat" : "Tambah Obat";
    if ($aksi == 'edit' && isset($_GET['id'])) {
        $id = mysqli_real_escape_string($koneksi, $_GET['id']);
        $edit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM obat WHERE id_obat='$id'"));
    }
    header_layout($judul_form, "Data Obat &nbsp;>&nbsp; $judul_form", "obat");
?>
    <div class="card-box">
        <form method="POST">
            <input type="hidden" name="id_obat" value="<?= $edit['id_obat'] ?>">
            <div class="form-grid">
                <div class="form-group form-full">
                    <label>Nama Obat</label>
                    <input type="text" name="nama_obat" value="<?= htmlspecialchars($edit['nama_obat']) ?>" placeholder="Contoh: Paracetamol" required>
                </div>
                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" name="satuan" value="<?= htmlspecialchars($edit['satuan']) ?>" placeholder="Tablet, Kapsul, Sirup" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" value="<?= htmlspecialchars($edit['stok']) ?>" placeholder="Jumlah stok" required>
                </div>
            </div>
            <div class="form-actions">
                <a href="obat.php" class="btn btn-secondary">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
<?php
else:
    header_layout("Data Obat", "Data Obat", "obat");
    $cari = $_GET['cari'] ?? '';
    $cari_esc = mysqli_real_escape_string($koneksi, $cari);
    $where = $cari ? "WHERE nama_obat LIKE '%$cari_esc%'" : "";
?>
    <div class="card-box">
        <div class="card-header">
            <form method="GET" class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari nama obat...">
            </form>
            <a href="obat.php?aksi=tambah" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Obat</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th><th>ID Obat</th><th>Nama Obat</th><th>Satuan</th><th>Stok</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $q = mysqli_query($koneksi, "SELECT * FROM obat $where ORDER BY id_obat ASC");
                while ($row = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                    <td><?= $no ?></td>
                    <td>O<?= str_pad($no, 3, '0', STR_PAD_LEFT) ?></td>
                    <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                    <td><?= htmlspecialchars($row['satuan']) ?></td>
                    <td><?= $row['stok'] ?></td>
                    <td>
                        <div class="action-group">
                            <a href="obat.php?aksi=edit&id=<?= $row['id_obat'] ?>" class="act-btn act-edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="obat.php?hapus=<?= $row['id_obat'] ?>" class="act-btn act-del" title="Hapus" onclick="return confirm('Yakin hapus data obat ini?')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php $no++; endwhile; ?>
            </tbody>
        </table>
    </div>
<?php endif; footer_layout(); ?>