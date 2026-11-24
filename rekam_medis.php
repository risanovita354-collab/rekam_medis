<?php
// Aktifkan tampilan error agar tidak muncul layar putih jika ada kesalahan
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';
include 'layout.php';

// Simpan Tambah / Edit
if (isset($_POST['simpan'])) {
    $id_rekam        = $_POST['id_rekam'];
    $id_pasien       = mysqli_real_escape_string($koneksi, $_POST['id_pasien']);
    $id_dokter       = mysqli_real_escape_string($koneksi, $_POST['id_dokter']);
    $id_obat         = mysqli_real_escape_string($koneksi, $_POST['id_obat']);
    $tanggal_periksa = mysqli_real_escape_string($koneksi, $_POST['tanggal_periksa']);
    $keluhan         = mysqli_real_escape_string($koneksi, $_POST['keluhan']);
    $diagnosa        = mysqli_real_escape_string($koneksi, $_POST['diagnosa']);
    $tindakan        = mysqli_real_escape_string($koneksi, $_POST['tindakan']);
    $resep           = mysqli_real_escape_string($koneksi, $_POST['resep']);

    try {
        if ($id_rekam == "") {
            $sql = "INSERT INTO rekam_medis (id_pasien, id_dokter, id_obat, tanggal_periksa, keluhan, diagnosa, tindakan, resep) 
                    VALUES ('$id_pasien','$id_dokter','$id_obat','$tanggal_periksa','$keluhan','$diagnosa','$tindakan','$resep')";
            $q = mysqli_query($koneksi, $sql);
            $msg = "Data pemeriksaan berhasil disimpan!";
        } else {
            $sql = "UPDATE rekam_medis SET id_pasien='$id_pasien', id_dokter='$id_dokter', id_obat='$id_obat', 
                    tanggal_periksa='$tanggal_periksa', keluhan='$keluhan', diagnosa='$diagnosa', tindakan='$tindakan', resep='$resep' 
                    WHERE id_rekam='$id_rekam'";
            $q = mysqli_query($koneksi, $sql);
            $msg = "Data pemeriksaan berhasil diperbarui!";
        }

        if (!$q) {
            throw new Exception(mysqli_error($koneksi));
        }

        echo "<script>alert('$msg'); window.location='rekam_medis.php';</script>";
        exit;
    } catch (Throwable $e) {
        $err = addslashes($e->getMessage());
        echo "<script>alert('Gagal menyimpan data! Penyebab: $err'); window.history.back();</script>";
        exit;
    }
}

// Hapus
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $q = mysqli_query($koneksi, "DELETE FROM rekam_medis WHERE id_rekam='$id'");
    $msg = $q ? "Data pemeriksaan berhasil dihapus!" : "Gagal menghapus data!";
    echo "<script>alert('$msg'); window.location='rekam_medis.php';</script>";
    exit;
}

$aksi = $_GET['aksi'] ?? 'list';

// FORM TAMBAH / EDIT PEMERIKSAAN
if ($aksi == 'tambah' || $aksi == 'edit'):
    $edit = ['id_rekam'=>'', 'id_pasien'=>'', 'id_dokter'=>'', 'id_obat'=>'', 'tanggal_periksa'=>date('Y-m-d'), 'keluhan'=>'', 'diagnosa'=>'', 'tindakan'=>'', 'resep'=>''];
    $judul_form = ($aksi == 'edit') ? "Edit Pemeriksaan Pasien" : "Pemeriksaan Pasien";
    if ($aksi == 'edit' && isset($_GET['id'])) {
        $id = mysqli_real_escape_string($koneksi, $_GET['id']);
        $edit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM rekam_medis WHERE id_rekam='$id'"));
    }
    header_layout($judul_form, "Pemeriksaan &nbsp;>&nbsp; $judul_form", "pemeriksaan");
?>
    <div class="card-box">
        <form method="POST">
            <input type="hidden" name="id_rekam" value="<?= $edit['id_rekam'] ?>">
            <div class="form-grid">
                <div class="form-group">
                    <label>Pasien</label>
                    <select name="id_pasien" required>
                        <option value="">Pilih Pasien...</option>
                        <?php
                        $qp = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY nama_pasien ASC");
                        while ($p = mysqli_fetch_assoc($qp)):
                        ?>
                        <option value="<?= $p['id_pasien'] ?>" <?= $edit['id_pasien']==$p['id_pasien']?'selected':'' ?>>
                            <?= $p['no_rm'] ?> - <?= $p['nama_pasien'] ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Dokter</label>
                    <select name="id_dokter" required>
                        <option value="">Pilih Dokter...</option>
                        <?php
                        $qd = mysqli_query($koneksi, "SELECT * FROM dokter ORDER BY nama_dokter ASC");
                        while ($d = mysqli_fetch_assoc($qd)):
                        ?>
                        <option value="<?= $d['id_dokter'] ?>" <?= $edit['id_dokter']==$d['id_dokter']?'selected':'' ?>>
                            <?= $d['nama_dokter'] ?> (<?= $d['spesialisasi'] ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Obat</label>
                    <select name="id_obat" required>
                        <option value="">Pilih Obat...</option>
                        <?php
                        $qo = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY nama_obat ASC");
                        while ($o = mysqli_fetch_assoc($qo)):
                        ?>
                        <option value="<?= $o['id_obat'] ?>" <?= $edit['id_obat']==$o['id_obat']?'selected':'' ?>>
                            <?= $o['nama_obat'] ?> (<?= $o['satuan'] ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Periksa</label>
                    <input type="date" name="tanggal_periksa" value="<?= $edit['tanggal_periksa'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Keluhan</label>
                    <textarea name="keluhan" rows="3" placeholder="Masukkan keluhan pasien..." required><?= htmlspecialchars($edit['keluhan']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Diagnosa</label>
                    <textarea name="diagnosa" rows="3" placeholder="Masukkan diagnosa..." required><?= htmlspecialchars($edit['diagnosa']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Tindakan</label>
                    <textarea name="tindakan" rows="3" placeholder="Masukkan tindakan..." required><?= htmlspecialchars($edit['tindakan']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Resep</label>
                    <textarea name="resep" rows="3" placeholder="Masukkan resep obat..." required><?= htmlspecialchars($edit['resep']) ?></textarea>
                </div>
            </div>
            <div class="form-actions">
                <a href="rekam_medis.php" class="btn btn-secondary">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>

<?php
// DETAIL REKAM MEDIS
elseif ($aksi == 'detail' && isset($_GET['id'])):
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $detail = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT r.*, p.*, d.nama_dokter, d.spesialisasi, o.nama_obat, o.satuan
        FROM rekam_medis r
        INNER JOIN pasien p ON r.id_pasien = p.id_pasien
        INNER JOIN dokter d ON r.id_dokter = d.id_dokter
        INNER JOIN obat o ON r.id_obat = o.id_obat
        WHERE r.id_rekam = '$id'"));
    header_layout("Detail Rekam Medis", "Pemeriksaan &nbsp;>&nbsp; Detail Rekam Medis", "pemeriksaan");
?>
    <div class="card-box">
        <div class="card-header" style="justify-content: flex-end;">
            <a href="rekam_medis.php" class="btn btn-primary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="detail-grid">
            <div>
                <div class="info-box">
                    <h3>Informasi Pasien</h3>
                    <table class="info-table">
                        <tr><td>No. RM</td><td>: <?= htmlspecialchars($detail['no_rm']) ?></td></tr>
                        <tr><td>Nama Pasien</td><td>: <?= htmlspecialchars($detail['nama_pasien']) ?></td></tr>
                        <tr><td>Jenis Kelamin</td><td>: <?= $detail['jenis_kelamin']=='L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
                        <tr><td>Tanggal Lahir</td><td>: <?= date('d-m-Y', strtotime($detail['tanggal_lahir'])) ?></td></tr>
                        <tr><td>Alamat</td><td>: <?= htmlspecialchars($detail['alamat']) ?></td></tr>
                    </table>
                </div>
                <div class="info-box" style="margin-bottom:0;">
                    <h3>Informasi Pemeriksaan</h3>
                    <table class="info-table">
                        <tr><td>Tanggal Periksa</td><td>: <?= date('d-m-Y', strtotime($detail['tanggal_periksa'])) ?></td></tr>
                        <tr><td>Dokter</td><td>: <?= htmlspecialchars($detail['nama_dokter']) ?> (<?= htmlspecialchars($detail['spesialisasi']) ?>)</td></tr>
                        <tr><td>Obat</td><td>: <?= htmlspecialchars($detail['nama_obat']) ?> (<?= htmlspecialchars($detail['satuan']) ?>)</td></tr>
                        <tr><td>Keluhan</td><td>: <?= htmlspecialchars($detail['keluhan']) ?></td></tr>
                        <tr><td>Diagnosa</td><td>: <?= htmlspecialchars($detail['diagnosa']) ?></td></tr>
                        <tr><td>Tindakan</td><td>: <?= htmlspecialchars($detail['tindakan']) ?></td></tr>
                        <tr><td>Resep</td><td>: <?= htmlspecialchars($detail['resep']) ?></td></tr>
                    </table>
                </div>
            </div>
            <div class="quote-card">
                <i class="fa-solid fa-clipboard-list"></i>
                <p>"Kesehatan adalah investasi terbaik dalam hidup"</p>
            </div>
        </div>
    </div>

<?php
// DATA PEMERIKSAAN (SQL JOIN)
else:
    header_layout("Data Pemeriksaan", "Data Pemeriksaan", "pemeriksaan");
    $cari = $_GET['cari'] ?? '';
    $cari_esc = mysqli_real_escape_string($koneksi, $cari);
    $where = $cari ? "WHERE p.nama_pasien LIKE '%$cari_esc%' OR d.nama_dokter LIKE '%$cari_esc%' OR o.nama_obat LIKE '%$cari_esc%'" : "";
?>
    <div class="card-box">
        <div class="card-header">
            <form method="GET" class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari nama pasien, dokter, atau obat...">
            </form>
            <a href="rekam_medis.php?aksi=tambah" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Pemeriksaan</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th><th>Tanggal Periksa</th><th>Pasien</th><th>Dokter</th><th>Obat</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $sql = "SELECT r.*, p.nama_pasien, d.nama_dokter, o.nama_obat
                        FROM rekam_medis r
                        INNER JOIN pasien p ON r.id_pasien = p.id_pasien
                        INNER JOIN dokter d ON r.id_dokter = d.id_dokter
                        INNER JOIN obat o ON r.id_obat = o.id_obat
                        $where ORDER BY r.id_rekam DESC";
                $q = mysqli_query($koneksi, $sql);
                while ($row = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal_periksa'])) ?></td>
                    <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                    <td><?= htmlspecialchars($row['nama_dokter']) ?></td>
                    <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                    <td>
                        <div class="action-group">
                            <a href="rekam_medis.php?aksi=detail&id=<?= $row['id_rekam'] ?>" class="act-btn act-view" title="Lihat Detail"><i class="fa-solid fa-eye"></i></a>
                            <a href="rekam_medis.php?aksi=edit&id=<?= $row['id_rekam'] ?>" class="act-btn act-edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="rekam_medis.php?hapus=<?= $row['id_rekam'] ?>" class="act-btn act-del" title="Hapus" onclick="return confirm('Yakin hapus data pemeriksaan ini?')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php endif; footer_layout(); ?>