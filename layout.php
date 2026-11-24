<?php
function header_layout($judul, $breadcrumb, $active_menu) {
    $tanggal = date('l, d F Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul ?> - Rekam Medis Sederhana</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="app-container">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon"><i class="fa-solid fa-heart-pulse"></i></div>
            <span>Rekam Medis</span>
        </div>
        <ul class="menu-list">
            <li><a href="index.php" class="<?= $active_menu=='dashboard'?'active':'' ?>"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="pasien.php" class="<?= $active_menu=='pasien'?'active':'' ?>"><i class="fa-solid fa-user-group"></i> Data Pasien</a></li>
            <li><a href="dokter.php" class="<?= $active_menu=='dokter'?'active':'' ?>"><i class="fa-solid fa-user-doctor"></i> Data Dokter</a></li>
            <li><a href="obat.php" class="<?= $active_menu=='obat'?'active':'' ?>"><i class="fa-solid fa-pills"></i> Data Obat</a></li>
            <li><a href="rekam_medis.php" class="<?= $active_menu=='pemeriksaan'?'active':'' ?>"><i class="fa-solid fa-file-medical"></i> Pemeriksaan</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div class="page-title">
                <h1><?= $judul ?></h1>
                <div class="breadcrumb"><i class="fa-solid fa-house"></i> &nbsp;<?= $breadcrumb ?></div>
            </div>
            <div class="topbar-right">
                <span><i class="fa-regular fa-calendar"></i> &nbsp;<?= $tanggal ?></span>
                <div class="user-badge">
                    <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                    <span>Petugas</span>
                </div>
            </div>
        </div>
<?php
}

function footer_layout() {
    echo "</main></div></body></html>";
}
?>