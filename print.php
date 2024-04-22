<?php
@ob_start();
session_start();
if (!empty($_SESSION['admin'])) {
} else {
	echo '<script>window.location="login.php";</script>';
	exit;
}
require 'config.php';
include $view;
$lihat = new view($config);
$toko = $lihat->toko();
$hsl = $lihat->penjualan();
?>
<html>

<head>
	<title>print</title>
	<link rel="stylesheet" href="assets/css/bootstrap.css">
	<style>
		table {
			border-collapse: collapse;
			width: 100%;
		}

		table,
		th,
		td {
			border: 1px solid #ddd;
		}

		th,
		td {
			padding: 8px;
			text-align: left;
		}

		th {
			background-color: #f2f2f2;
		}
	</style>

</head>

<body>
	<script>
		window.print();
	</script>
	<div class="container">
		<div class="row">
			<div class="col-sm-4">
				<center>
					<p><b>UD. SUMBER BANGUNAN</b></p>
					<p><b>Jl. Raya Cerme Lor No. 149</b></p>
					<p>Tanggal : <?php echo date("j F Y, G:i"); ?></p>
					<p>Kasir : <?php echo htmlentities($_GET['nm_member']); ?></p>
				</center>
				<hr />
				<br />
				<p><b>Nama Pembeli:</b> <?php echo htmlentities($_GET['nama_pembeli']); ?></p>
				<p><b>Alamat:</b> <?php echo htmlentities($_GET['alamat_pembeli']); ?></p>
				<p><b>No. Telp:</b> <?php echo htmlentities($_GET['telepon_pembeli']); ?></p>
				<table width='100%' border="1">
					<tr>
						<td style="width:1%;"><b>No.</b></td>
						<td><b>Barang</b></td>
						<td style="width:15%;"><b>Satuan</b></td>
						<td style="width:10%;"><b>Jumlah</b></td>
						<td style="width:15%;"><b>Harga Jual</b></td>
						<td><b>Total</b></td>
					</tr>
					<?php $no = 1;
					foreach ($hsl as $isi) { ?>
						<tr>
							<td><?php echo $no; ?></td>
							<td><?php echo $isi['nama_barang']; ?></td>
							<td><?php echo $isi['satuan_barang']; ?></td>
							<td><?php echo $isi['jumlah']; ?></td>
							<td><?php echo $isi['harga_jual']; ?></td>
							<td><?php echo $isi['total']; ?></td>
						</tr>
					<?php $no++;
					} ?>
				</table>
				<br />
				<div class="pull-right">
					<?php $hasil = $lihat->jumlah(); ?>
					Total : Rp.<?php echo number_format($hasil['bayar']); ?>,-
					<br />
					Bayar : Rp.<?php echo number_format(htmlentities($_GET['bayar'])); ?>,-
					<br />
					Kembali : Rp.<?php echo number_format(htmlentities($_GET['kembali'])); ?>,-
				</div>
				<div class="clearfix"></div>
				<center>
					<p>Terima Kasih Telah berbelanja di toko kami !</p>
				</center>
			</div>
			<div class="col-sm-4"></div>
		</div>
	</div>
</body>

</html>