 <?php
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
 <div class="row">
 	<div class="col-md-12">
 		<h4>
 			<!--<a  style="padding-left:2pc;" href="fungsi/hapus/hapus.php?laporan=jual" onclick="javascript:return confirm('Data Laporan akan di Hapus ?');">
						<button class="btn btn-danger">RESET</button>
					</a>-->
 			<?php if (!empty($_GET['cari'])) { ?>
 				Data Laporan Penjualan <?= $bulan_tes[$_POST['bln']]; ?> <?= $_POST['thn']; ?>
 			<?php } elseif (!empty($_GET['hari'])) { ?>
 				Data Laporan Penjualan <?= $_POST['hari']; ?>
 			<?php } else { ?>
 				Data Laporan Penjualan <?= $bulan_tes[date('m')]; ?> <?= date('Y'); ?>
 			<?php } ?>
 		</h4>
 		<br />
 		<div class="card">
 			<div class="card-header">
 				<h5 class="card-title mt-2">Cari Laporan Per Bulan</h5>
 			</div>
 			<div class="card-body p-0">
 				<form method="post" action="index.php?page=laporan&cari=ok">
 					<table class="table table-striped">
 						<tr>
 							<th>
 								Pilih Bulan
 							</th>
 							<th>
 								Pilih Tahun
 							</th>
 							<th>
 								Aksi
 							</th>
 						</tr>
 						<tr>
 							<td>
 								<select name="bln" class="form-control">
 									<option selected="selected">Bulan</option>
 									<?php
										$bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
										$jlh_bln = count($bulan);
										$bln1 = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
										$no = 1;
										for ($c = 0; $c < $jlh_bln; $c += 1) {
											echo "<option value='$bln1[$c]'> $bulan[$c] </option>";
											$no++;
										}
										?>
 								</select>
 							</td>
 							<td>
 								<?php
									$now = date('Y');
									echo "<select name='thn' class='form-control'>";
									echo '
								<option selected="selected">Tahun</option>';
									for ($a = 2017; $a <= $now; $a++) {
										echo "<option value='$a'>$a</option>";
									}
									echo "</select>";
									?>
 							</td>
 							<td>
 								<input type="hidden" name="periode" value="ya">
 								<button class="btn btn-primary">
 									<i class="fa fa-search"></i> Cari
 								</button>
 								<a href="index.php?page=laporan" class="btn btn-success">
 									<i class="fa fa-refresh"></i> Refresh</a>

 								<?php if (!empty($_GET['cari'])) { ?>
 									<a href="excel.php?cari=yes&bln=<?= $_POST['bln']; ?>&thn=<?= $_POST['thn']; ?>" class="btn btn-info"><i class="fa fa-download"></i>
 										Excel</a>
 								<?php } else { ?>
 									<a href="excel.php" class="btn btn-info"><i class="fa fa-download"></i>
 										Excel</a>
 								<?php } ?>
 							</td>
 						</tr>
 					</table>
 				</form>
 				<form method="post" action="index.php?page=laporan&hari=cek">
 					<table class="table table-striped">
 						<tr>
 							<th>
 								Pilih Hari
 							</th>
 							<th>
 								Aksi
 							</th>
 						</tr>
 						<tr>
 							<td>
 								<input type="date" value="<?= date('Y-m-d'); ?>" class="form-control" name="hari">
 							</td>
 							<td>
 								<input type="hidden" name="periode" value="ya">
 								<button class="btn btn-primary">
 									<i class="fa fa-search"></i> Cari
 								</button>
 								<a href="index.php?page=laporan" class="btn btn-success">
 									<i class="fa fa-refresh"></i> Refresh</a>

 								<?php if (!empty($_GET['hari'])) { ?>
 									<a href="excel.php?hari=cek&tgl=<?= $_POST['hari']; ?>" class="btn btn-info"><i class="fa fa-download"></i>
 										Excel</a>
 								<?php } else { ?>
 									<a href="excel.php" class="btn btn-info"><i class="fa fa-download"></i>
 										Excel</a>
 								<?php } ?>
 							</td>
 						</tr>
 					</table>
 				</form>
 			</div>
 		</div>
 		<br />
 		<br />
 		<!-- view barang -->
 		<div class="card">
 			<div class="card-body">
 				<div class="table-responsive">
 					<table class="table table-bordered w-100 table-sm" id="example1">
 						<thead>
 							<tr style="background:#DFF0D8;color:#333;">
 								<th> No</th>
 								<th> Nama Barang</th>
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
 										<td><?php echo $isi['jumlah']; ?> </td>
 										<td>Rp.<?php echo number_format($isi['harga_beli'] * $isi['jumlah']); ?>,-</td>
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
 									<th colspan="2">Total Terjual</th>
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
 								<th colspan="2">Total Terjual</th>
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
 			</div>
 		</div>
 	</div>
 </div>