<?php
include 'koneksi.php';
include 'layout.php';

$jml_pasien = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS t FROM pasien"))['t'];
$jml_dokter = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS t FROM dokter"))['t'];
$jml_obat   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS t FROM obat"))['t'];
$jml_rekam  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS t FROM rekam_medis"))['t'];

header_layout("Dashboard", "Beranda", "dashboard");
?>

<div class="welcome-banner">
    <div>
        <h2>Sistem Informasi Rekam Medis</h2>
        <p>Kelola data pasien, dokter, obat, dan riwayat pemeriksaan dengan mudah.</p>
    </div>
    <div class="banner-badge"><i class="fa-solid fa-user-nurse"></i></div>
</div>

<div class="stats-grid">
    <div class="stat-card c1">
        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
        <div class="stat-label">Total Pasien</div>
        <div class="stat-value"><?= $jml_pasien ?></div>
    </div>
    <div class="stat-card c2">
        <div class="stat-icon"><i class="fa-solid fa-user-doctor"></i></div>
        <div class="stat-label">Total Dokter</div>
        <div class="stat-value"><?= $jml_dokter ?></div>
    </div>
    <div class="stat-card c3">
        <div class="stat-icon"><i class="fa-solid fa-capsules"></i></div>
        <div class="stat-label">Total Obat</div>
        <div class="stat-value"><?= $jml_obat ?></div>
    </div>
    <div class="stat-card c4">
        <div class="stat-icon"><i class="fa-solid fa-clipboard-check"></i></div>
        <div class="stat-label">Total Pemeriksaan</div>
        <div class="stat-value"><?= $jml_rekam ?></div>
    </div>
</div>

<div class="card-box">
    <div class="card-header">
        <h3 style="font-size:16px;">Pemeriksaan Terbaru</h3>
        <a href="rekam_medis.php" style="font-size:13px; color:var(--primary); text-decoration:none; font-weight:600;">Lihat Semua &rarr;</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pasien</th>
                <th>Dokter</th>
                <th>Tanggal Periksa</th>
                <th>Diagnosa</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $q = mysqli_query($koneksi, "SELECT r.*, p.nama_pasien, d.nama_dokter 
                                         FROM rekam_medis r
                                         INNER JOIN pasien p ON r.id_pasien = p.id_pasien
                                         INNER JOIN dokter d ON r.id_dokter = d.id_dokter
                                         ORDER BY r.id_rekam DESC LIMIT 5");
            while ($row = mysqli_fetch_assoc($q)):
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                <td><?= htmlspecialchars($row['nama_dokter']) ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal_periksa'])) ?></td>
                <td><?= htmlspecialchars($row['diagnosa']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php footer_layout(); ?>