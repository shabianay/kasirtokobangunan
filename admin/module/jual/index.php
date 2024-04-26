 <!--sidebar end-->

 <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
 <!--main content start-->
 <?php
	$id = $_SESSION['admin']['id_member'];
	$hasil = $lihat->member_edit($id);
	?>
 <h4>Keranjang Penjualan</h4>
 <br>
 <?php if (isset($_GET['success'])) { ?>
 	<div class="alert alert-success">
 		<p>Tambah Data Berhasil !</p>
 	</div>
 <?php } ?>
 <?php if (isset($_GET['successs'])) { ?>
 	<div class="alert alert-success">
 		<p>Tambah Data Berhasil !</p>
 	</div>
 <?php } ?>
 <?php if (isset($_GET['remove'])) { ?>
 	<div class="alert alert-danger">
 		<p>Hapus Data Berhasil !</p>
 	</div>
 <?php } ?>

 <style>
 	tr.total-sum {
 		border-top: 2px solid #c9c9c9;
 		/* Adjust the thickness and color as needed */
 	}
 </style>

 <div class="row">
 	<div class="col-sm-4">
 		<div class="card card-primary mb-3">
 			<div class="card-header bg-primary text-white">
 				<h5><i class="fa fa-search"></i> Cari Barang</h5>
 			</div>
 			<div class="card-body">
 				<input type="text" id="cari" class="form-control" name="cari" placeholder="Masukan : Kode / Nama Barang  [ENTER]">
 			</div>
 		</div>
 	</div>
 	<div class="col-sm-8">
 		<div class="card card-primary mb-3">
 			<div class="card-header bg-primary text-white">
 				<h5><i class="fa fa-list"></i> Hasil Pencarian</h5>
 			</div>
 			<div class="card-body">
 				<div class="table-responsive">
 					<div id="hasil_cari"></div>
 					<div id="tunggu"></div>
 				</div>
 			</div>
 		</div>
 	</div>


 	<div class="col-sm-12">
 		<div class="card card-primary">
 			<div class="card-header bg-primary text-white">
 				<h5><i class="fa fa-shopping-cart"></i> KASIR PENJUALAN
 					<a class="btn btn-danger float-right" onclick="javascript:return confirm('Apakah anda ingin reset keranjang ?');" href="fungsi/hapus/hapus.php?penjualan=jual">
 						<b>RESET KERANJANG</b></a>
 				</h5>
 			</div>
 			<div class="card-body">
 				<div class="row">
 					<div class="col-sm-4">
 						<div class="card card-primary mb-3">
 							<div class="card-header bg-primary text-white">
 								<h5><i class="fa fa-search"></i> Cari Data Pembeli</h5>
 							</div>
 							<div class="card-body">
 								<input type="text" id="pembeli" class="form-control" name="pembeli" placeholder="Masukan : Nama Pembeli  [ENTER]">
 							</div>
 						</div>
 					</div>
 					<div class="col-sm-8">
 						<div class="card card-primary mb-3">
 							<div class="card-header bg-primary text-white">
 								<h5><i class="fa fa-list"></i> Hasil Pencarian</h5>
 							</div>
 							<div class="card-body">
 								<div class="table-responsive">
 									<div id="hasil_cari_pembeli"></div>
 									<div id="tunggu_pembeli"></div>
 								</div>
 							</div>
 						</div>
 					</div>
 				</div>
 				<table class="table table-bordered w-100" id="example1">
 					<thead>
 						<tr>
 							<td> No</td>
 							<td> Nama Barang</td>
 							<td style="width:10%;"> Jumlah</td>
 							<td style="width:10%;"> Satuan</td>
 							<td style="width:15%;"> Harga Jual</td>
 							<td style="width:20%;"> Total</td>
 							<td> Kasir</td>
 							<td> Aksi</td>
 						</tr>
 					</thead>
 					<tbody>
 						<?php $total_bayar = 0;
							$no = 1;
							$hasil_penjualan = $lihat->penjualan(); ?>
 						<?php foreach ($hasil_penjualan  as $isi) { ?>
 							<tr>
 								<td><?php echo $no; ?></td>
 								<td><?php echo $isi['nama_barang']; ?></td>
 								<td>
 									<!-- aksi ke table penjualan -->
 									<form method="POST" action="fungsi/edit/edit.php?jual=jual">
 										<input type="number" name="jumlah" value="<?php echo $isi['jumlah']; ?>" class="form-control">
 										<input type="hidden" name="id" value="<?php echo $isi['id_penjualan']; ?>" class="form-control">
 										<input type="hidden" name="id_barang" value="<?php echo $isi['id_barang']; ?>" class="form-control">
 								</td>
 								<td><?php echo $isi['satuan_barang']; ?></td>
 								<td>
 									<form method="POST" action="fungsi/edit/edit.php?jual=jual">
 										<input type="number" name="harga_jual" value="<?php echo $isi['harga_jual']; ?>" class="form-control">
 								</td>
 								<td>Rp.<?php echo number_format($isi['total']); ?>,-</td>
 								<td><?php echo $isi['nm_member']; ?></td>
 								<td>
 									<button type="submit" class="btn btn-warning">Update</button>
 									</form>
 									<!-- aksi ke table penjualan -->
 									<a href="fungsi/hapus/hapus.php?jual=jual&id=<?php echo $isi['id_penjualan']; ?>&brg=<?php echo $isi['id_barang']; ?>
					&jml=<?php echo $isi['jumlah']; ?>" class="btn btn-danger"><i class="fa fa-times"></i>
 									</a>
 								</td>
 							</tr>
 						<?php
								$no++;
								$total_bayar += $isi['total'];
							}
							?>
 					</tbody>
 				</table>
 				<br />
 				<?php $hasil = $lihat->jumlah(); ?>
 				<div id="kasirnya">
 					<table class="table table-stripped">
 						<?php
							// proses bayar dan ke nota
							if (!empty($_GET['nota'] == 'yes')) {
								$total = $_POST['total'];
								$bayar = isset($_POST['bayar']) ? $_POST['bayar'] : 0;
								if (!empty($bayar)) {
									$hitung = $bayar - $total;
									if ($bayar >= $total) {
										$id_barang = $_POST['id_barang'];
										$id_member = $_POST['id_member'];
										$jumlah = $_POST['jumlah'];
										$total = $_POST['total1'];
										$tgl_input = $_POST['tgl_input'];
										$satuan_barang = $_POST['satuan_barang'];
										$harga_jual = $_POST['harga_jual'];
										$periode = $_POST['periode'];
										$nama_pembeli = $_POST['nama_pembeli'];
										$alamat_pembeli = $_POST['alamat_pembeli'];
										$telepon_pembeli = $_POST['telepon_pembeli'];

										$jumlah_dipilih = count($id_barang);

										for ($x = 0; $x < $jumlah_dipilih; $x++) {
											// aksi ke table nota
											$d = array($id_barang[$x], $id_member[$x], $jumlah[$x], $total[$x], $tgl_input[$x], $satuan_barang[$x], $harga_jual[$x], $periode[$x]);
											$sql = "INSERT INTO nota (id_barang, id_member, jumlah, total, tanggal_input, satuan_barang, harga_jual, periode, nama_pembeli, alamat_pembeli, telepon_pembeli) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
											$row = $config->prepare($sql);
											$row->execute([
												$id_barang[$x], $id_member[$x], $jumlah[$x], $total[$x], $tgl_input[$x], $satuan_barang[$x], $harga_jual[$x], $periode[$x], $nama_pembeli, $alamat_pembeli, $telepon_pembeli
											]);

											// ubah stok barang
											$sql_barang = "SELECT * FROM barang WHERE id_barang = ?";
											$row_barang = $config->prepare($sql_barang);
											$row_barang->execute(array($id_barang[$x]));
											$hsl = $row_barang->fetch();

											$stok = $hsl['stok'];
											$idb  = $hsl['id_barang'];

											$total_stok = $stok - $jumlah[$x];
											// echo $total_stok;
											$sql_stok = "UPDATE barang SET stok = ? WHERE id_barang = ?";
											$row_stok = $config->prepare($sql_stok);
											$row_stok->execute(array($total_stok, $idb));
										}
										echo '<script>alert("Belanjaan Berhasil Di Bayar !");</script>';
									} else {
										echo '<script>alert("Uang Kurang ! Rp.' . $hitung . '");</script>';
									}
								}
							}
							?>
 						<!-- aksi ke table nota -->
 						<form method="POST" action="index.php?page=jual&nota=yes#kasirnya">
 							<?php foreach ($hasil_penjualan as $isi) {; ?>
 								<input type="hidden" name="id_barang[]" value="<?php echo $isi['id_barang']; ?>">
 								<input type="hidden" name="id_member[]" value="<?php echo $isi['id_member']; ?>">
 								<input type="hidden" name="jumlah[]" value="<?php echo $isi['jumlah']; ?>">
 								<input type="hidden" name="total1[]" value="<?php echo $isi['total']; ?>">
 								<input type="hidden" name="tgl_input[]" value="<?php echo $isi['tanggal_input']; ?>">
 								<input type="hidden" name="satuan_barang[]" value="<?php echo $isi['satuan_barang']; ?>">
 								<input type="hidden" name="harga_jual[]" value="<?php echo $isi['harga_jual']; ?>">
 								<input type="hidden" name="periode[]" value="<?php echo date('m-Y'); ?>">
 							<?php $no++;
								} ?>
 							<tr>
 								<td>Nama Pembeli</td>
 								<td><input type="text" class="form-control" id="nama_pembeli" name="nama_pembeli"></td>
 							</tr>
 							<tr>
 								<td>Alamat Pembeli</td>
 								<td><input class="form-control" id="alamat_pembeli" name="alamat_pembeli" rows="3"></td>
 							</tr>
 							<tr>
 								<td>Telepon Pembeli</td>
 								<td><input type="tel" class="form-control" id="telepon_pembeli" name="telepon_pembeli"></td>
 							</tr>
 							<tr class="total-sum">
 								<td><b>Total Semua </b></td>
 								<td><input type="text" class="form-control" name="total" value="<?php echo $total_bayar; ?>"></td>
 								<td><b>Bayar </b></td>
 								<td><input type="text" class="form-control" name="bayar" value="<?php echo $bayar; ?>"></td>
 								<td><button class="btn btn-success"><i class="fa fa-shopping-cart"></i> Bayar</button>
 									<?php if (!empty($_GET['nota'] == 'yes')) { ?>
 										<a class="btn btn-danger" href="fungsi/hapus/hapus.php?penjualan=jual">
 											<b>RESET</b></a>
 								</td><?php } ?></td>
 							</tr>
 						</form>
 						<!-- aksi ke table nota -->
 						<tr>
 							<td><b>Kembali</b></td>
 							<td><input type="text" class="form-control" value="<?php echo $hitung; ?>"></td>
 							<td></td>
 							<td>
 								<a href="print.php?nm_member=<?php echo $_SESSION['admin']['nm_member']; ?>
									&bayar=<?php echo $bayar; ?>&kembali=<?php echo $hitung; ?>&nama_pembeli=<?php echo $nama_pembeli ?>&alamat_pembeli=<?php echo $alamat_pembeli ?>&telepon_pembeli=<?php echo $telepon_pembeli ?>" target="_blank">
 									<button class="btn btn-secondary">
 										<i class="fa fa-print"></i> Cetak Struk
 									</button></a>
 							</td>
 						</tr>
 					</table>
 					<br />
 					<br />
 				</div>
 			</div>
 		</div>
 	</div>
 </div>


 <script>
 	// AJAX call for autocomplete 
 	$(document).ready(function() {
 		$("#cari").change(function() {
 			$.ajax({
 				type: "POST",
 				url: "fungsi/edit/edit.php?cari_barang=yes",
 				data: 'keyword=' + $(this).val(),
 				beforeSend: function() {
 					$("#hasil_cari").hide();
 					$("#tunggu").html('<p style="color:green"><blink>tunggu sebentar</blink></p>');
 				},
 				success: function(html) {
 					$("#tunggu").html('');
 					$("#hasil_cari").show();
 					$("#hasil_cari").html(html);
 				}
 			});
 		});
 	});
 </script>

 <script>
 	// AJAX call for autocomplete 
 	$(document).ready(function() {
 		$("#pembeli").change(function() {
 			$.ajax({
 				type: "POST",
 				url: "fungsi/edit/edit.php?cari_pembeli=yes",
 				data: 'keyword=' + $(this).val(),
 				beforeSend: function() {
 					$("#hasil_cari_pembeli").hide();
 					$("#tunggu_pembeli").html('<p style="color:green"><blink>tunggu sebentar</blink></p>');
 				},
 				success: function(html) {
 					$("#tunggu_pembeli").html('');
 					$("#hasil_cari_pembeli").show();
 					$("#hasil_cari_pembeli").html(html);
 				}
 			});
 		});
 	});
 </script>

 <script>
 	var dataPembeli = {}; // Variabel untuk menyimpan data pembeli

 	function tambahPembeli(id_nota) {
 		// Lakukan AJAX request untuk mendapatkan data pembeli berdasarkan id_nota
 		$.ajax({
 			type: "GET",
 			url: "fungsi/edit/edit.php",
 			data: {
 				id_nota: id_nota
 			},
 			success: function(response) {
 				// Parse data JSON yang diterima
 				var data = JSON.parse(response);

 				// Simpan data pembeli dalam variabel
 				dataPembeli = {
 					nama_pembeli: data.nama_pembeli,
 					alamat_pembeli: data.alamat_pembeli,
 					telepon_pembeli: data.telepon_pembeli
 				};

 				// Isi nilai input dengan data pembeli yang dipilih
 				$("#nama_pembeli").val(data.nama_pembeli);
 				$("#alamat_pembeli").val(data.alamat_pembeli);
 				$("#telepon_pembeli").val(data.telepon_pembeli);
 			}
 		});
 	}

 	// Fungsi untuk menampilkan data pembeli yang disimpan dalam variabel
 	function tampilkanDataPembeli() {
 		$("#nama_pembeli").val(dataPembeli.nama_pembeli);
 		$("#alamat_pembeli").val(dataPembeli.alamat_pembeli);
 		$("#telepon_pembeli").val(dataPembeli.telepon_pembeli);
 	}
 </script>