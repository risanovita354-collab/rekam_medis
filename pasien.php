<?php
include 'koneksi.php';
include 'layout.php';

// Simpan Tambah / Edit
if (isset($_POST['simpan'])) {
    $id     = $_POST['id_pasien'];
    $no_rm  = mysqli_real_escape_string($koneksi, $_POST['no_rm']);
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_pasien']);
    $jk     = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tgl    = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    try {
        if ($id == "") {
            $q = mysqli_query($koneksi, "INSERT INTO pasien (no_rm, nama_pasien, jenis_kelamin, tanggal_lahir, alamat) VALUES ('$no_rm','$nama','$jk','$tgl','$alamat')");
            $msg = "Data pasien berhasil ditambahkan!";
        } else {
            $q = mysqli_query($koneksi, "UPDATE pasien SET no_rm='$no_rm', nama_pasien='$nama', jenis_kelamin='$jk', tanggal_lahir='$tgl', alamat='$alamat' WHERE id_pasien='$id'");
            $msg = "Data pasien berhasil diperbarui!";
        }
        if (!$q) throw new Exception(mysqli_error($koneksi));
        echo "<script>alert('$msg'); window.location='pasien.php';</script>";
        exit;
    } catch (Throwable $e) {
        echo "<script>alert('Gagal menyimpan data pasien!'); window.location='pasien.php';</script>";
        exit;
    }
}

// Hapus dengan Try-Catch agar tidak layar putih saat ditolak Foreign Key
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    try {
        $q = mysqli_query($koneksi, "DELETE FROM pasien WHERE id_pasien='$id'");
        if (!$q) throw new Exception(mysqli_error($koneksi));
        echo "<script>alert('Data pasien berhasil dihapus!'); window.location='pasien.php';</script>";
        exit;
    } catch (Throwable $e) {
        echo "<script>alert('Gagal menghapus! Pasien ini masih memiliki data riwayat pemeriksaan (terikat relasi Foreign Key).'); window.location='pasien.php';</script>";
        exit;
    }
}

$aksi = $_GET['aksi'] ?? 'list';

if ($aksi == 'tambah' || $aksi == 'edit'):
    $edit = ['id_pasien'=>'', 'no_rm'=>'', 'nama_pasien'=>'', 'jenis_kelamin'=>'', 'tanggal_lahir'=>'', 'alamat'=>''];
    $judul_form = "Tambah Pasien";
    if ($aksi == 'edit' && isset($_GET['id'])) {
        $id = mysqli_real_escape_string($koneksi, $_GET['id']);
        $edit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pasien WHERE id_pasien='$id'"));
        $judul_form = "Edit Pasien";
    }
    header_layout($judul_form, "Data Pasien &nbsp;>&nbsp; $judul_form", "pasien");
?>
    <div class="card-box">
        <form method="POST">
            <input type="hidden" name="id_pasien" value="<?= $edit['id_pasien'] ?>">
            <div class="form-grid">
                <div class="form-group form-full">
                    <label>No. Rekam Medis</label>
                    <input type="text" name="no_rm" value="<?= htmlspecialchars($edit['no_rm']) ?>" placeholder="Contoh: RM006" required>
                </div>
                <div class="form-group form-full">
                    <label>Nama Pasien</label>
                    <input type="text" name="nama_pasien" value="<?= htmlspecialchars($edit['nama_pasien']) ?>" placeholder="Masukkan nama pasien" required>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" required>
                        <option value="">Pilih jenis kelamin</option>
                        <option value="L" <?= $edit['jenis_kelamin']=='L'?'selected':'' ?>>Laki-laki (L)</option>
                        <option value="P" <?= $edit['jenis_kelamin']=='P'?'selected':'' ?>>Perempuan (P)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="<?= $edit['tanggal_lahir'] ?>" required>
                </div>
                <div class="form-group form-full">
                    <label>Alamat</label>
                    <textarea name="alamat" rows="3" placeholder="Masukkan alamat pasien..." required><?= htmlspecialchars($edit['alamat']) ?></textarea>
                </div>
            </div>
            <div class="form-actions">
                <a href="pasien.php" class="btn btn-secondary">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
<?php
else:
    header_layout("Data Pasien", "Data Pasien", "pasien");
    $cari = $_GET['cari'] ?? '';
    $cari_esc = mysqli_real_escape_string($koneksi, $cari);
    $where = $cari ? "WHERE nama_pasien LIKE '%$cari_esc%' OR no_rm LIKE '%$cari_esc%'" : "";
?>
    <div class="card-box">
        <div class="card-header">
            <form method="GET" class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari nama pasien atau no. RM...">
            </form>
            <a href="pasien.php?aksi=tambah" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Pasien</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th><th>No RM</th><th>Nama Pasien</th><th>Jenis Kelamin</th><th>Tanggal Lahir</th><th>Alamat</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $q = mysqli_query($koneksi, "SELECT * FROM pasien $where ORDER BY id_pasien ASC");
                while ($row = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['no_rm']) ?></td>
                    <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                    <td><?= $row['jenis_kelamin'] ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal_lahir'])) ?></td>
                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                    <td>
                        <div class="action-group">
                            <a href="pasien.php?aksi=edit&id=<?= $row['id_pasien'] ?>" class="act-btn act-edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="pasien.php?hapus=<?= $row['id_pasien'] ?>" class="act-btn act-del" title="Hapus" onclick="return confirm('Yakin hapus data pasien ini?')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php endif; footer_layout(); ?>