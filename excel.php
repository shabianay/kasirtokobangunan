<?php
@ob_start();
session_start();
if (!empty($_SESSION['admin'])) {
} else {
    echo '<script>window.location="login.php";</script>';
    exit;
}
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=data-laporan-" . date('Y-m-d') . ".xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);

require 'config.php';
include $view;
$lihat = new view($config);

$bulan_tes = array(
    '01' => "Januari",
    '02' => "Februari",
    '03' => "Maret",
    '04' => "April",
    '05' => "Mei",
    '06' => "Juni",
    '07' => "Juli",
    '08' => "Agustus",
    '09' => "September",
    '10' => "Oktober",
    '11' => "November",
    '12' => "Desember"
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <!-- view barang -->
    <div class="modal-view">
        <h3 style="text-align:center;">
            <?php if (!empty(htmlentities($_GET['cari']))) { ?>
                Data Laporan Penjualan <?= $bulan_tes[htmlentities($_GET['bln'])]; ?> <?= htmlentities($_GET['thn']); ?>
            <?php } elseif (!empty(htmlentities($_GET['hari']))) { ?>
                Data Laporan Penjualan <?= htmlentities($_GET['tgl']); ?>
            <?php } else { ?>
                Data Laporan Penjualan <?= $bulan_tes[date('m')]; ?> <?= date('Y'); ?>
            <?php } ?>
        </h3>
        <table border="1" width="100%" cellpadding="3" cellspacing="4">
            <thead>
                <tr bgcolor="yellow">
                    <th> No</th>
                    <th> Nama Barang</th>
                    <th> Satuan</th>
                    <th style="width:10%;"> Jumlah</th>
                    <th style="width:10%;"> Modal</th>
                    <th style="width:10%;"> Total</th>
                    <th> Kasir</th>
                    <th> Nama Pembeli</th>
                    <th> Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $kelompok_tanggal = array(); // Array untuk menyimpan data penjualan berdasarkan tanggal input
                $total_penjualan = 0; // Inisialisasi total penjualan
                $total_modal = 0; // Inisialisasi total modal
                $total_keuntungan = 0; // Inisialisasi total keuntungan
                $total_barang = 0; // Inisialisasi total jumlah barang

                if (!empty($_GET['cari'])) {
                    // Query data penjualan berdasarkan periode
                    $periode = $_POST['bln'] . '-' . $_POST['thn'];
                    $hasil = $lihat->periode_jual($periode);
                } elseif (!empty($_GET['hari'])) {
                    // Query data penjualan berdasarkan tanggal
                    $hari = $_POST['hari'];
                    $hasil = $lihat->hari_jual($hari);
                } else {
                    // Query data penjualan tanpa filter
                    $hasil = $lihat->jual();
                }

                // Kelompokkan data penjualan berdasarkan tanggal input
                foreach ($hasil as $isi) {
                    $tanggal_input = $isi['tanggal_input'];
                    $kelompok_tanggal[$tanggal_input][] = $isi;
                }

                // Tampilkan data penjualan sesuai kelompok tanggal
                foreach ($kelompok_tanggal as $tanggal => $data_tanggal) {
                    $bayar = 0;
                    $jumlah = 0;
                    $modal = 0;
                    foreach ($data_tanggal as $isi) {
                        $bayar += $isi['total'];
                        $modal += $isi['harga_beli'] * $isi['jumlah'];
                        $jumlah += $isi['jumlah'];
                        $total_penjualan += $isi['total'];
                        $total_modal += $isi['harga_beli'] * $isi['jumlah'];
                        $total_keuntungan += $isi['total'] - ($isi['harga_beli'] * $isi['jumlah']);
                        $total_barang += $isi['jumlah']; // Tambahkan jumlah barang ke total_barang
                ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $isi['nama_barang']; ?></td>
                            <td><?php echo $isi['satuan_barang']; ?></td>
                            <td><?php echo $isi['jumlah']; ?> </td>
                            <td>Rp.<?php echo number_format($isi['harga_beli']); ?>,-</td>
                            <td>Rp.<?php echo number_format($isi['total']); ?>,-</td>
                            <td><?php echo $isi['nm_member']; ?></td>
                            <td><?php echo $isi['nama_pembeli']; ?></td>
                            <td><?php echo $isi['tanggal_input']; ?></td>
                        </tr>
                    <?php
                        $no++;
                    }
                    // Tampilkan total penjualan untuk setiap kelompok tanggal
                    ?>
                    <tr>
                        <th colspan="3">Total Terjual</th>
                        <th><?php echo $jumlah; ?></th>
                        <th>Rp.<?php echo number_format($modal); ?>,-</th>
                        <th>Rp.<?php echo number_format($bayar); ?>,-</th>
                        <th colspan="2" style="background:#0bb365;color:#fff;">Keuntungan</th>
                        <th style="background:#0bb365;color:#fff;">
                            Rp.<?php echo number_format($bayar - $modal); ?>,-</th>
                    </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total Terjual</th>
                    <th><?php echo $total_barang; ?></th> <!-- Tampilkan total jumlah barang -->
                    <th>Rp.<?php echo number_format($total_modal); ?>,-</th>
                    <th>Rp.<?php echo number_format($total_penjualan); ?>,-</th>
                    <th colspan="2" style="background:#0bb365;color:#fff;">Total Keuntungan</th>
                    <th style="background:#0bb365;color:#fff;">
                        Rp.<?php echo number_format($total_keuntungan); ?>,-</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>