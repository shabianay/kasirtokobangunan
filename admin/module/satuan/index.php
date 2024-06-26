<h4>Satuan</h4>
<br />
<?php if(isset($_GET['success'])){?>
<div class="alert alert-success">
    <p>Tambah Data Berhasil !</p>
</div>
<?php }?>
<?php if(isset($_GET['success-edit'])){?>
<div class="alert alert-success">
    <p>Update Data Berhasil !</p>
</div>
<?php }?>
<?php if(isset($_GET['remove'])){?>
<div class="alert alert-danger">
    <p>Hapus Data Berhasil !</p>
</div>
<?php }?>
<?php 
	if(!empty($_GET['uid'])){
	$sql = "SELECT * FROM satuan WHERE id_satuan = ?";
	$row = $config->prepare($sql);
	$row->execute(array($_GET['uid']));
	$edit = $row->fetch();
?>
<form method="POST" action="fungsi/edit/edit.php?satuan=edit">
    <table>
        <tr>
            <td style="width:25pc;"><input type="text" class="form-control" value="<?= $edit['nama_satuan'];?>"
                    required name="satuan" placeholder="Masukan Satuan Barang Baru">
                <input type="hidden" name="id" value="<?= $edit['id_satuan'];?>">
            </td>
            <td style="padding-left:10px;"><button id="tombol-simpan" class="btn btn-primary"><i class="fa fa-edit"></i>
                    Ubah Data</button></td>
        </tr>
    </table>
</form>
<?php }else{?>
<form method="POST" action="fungsi/tambah/tambah.php?satuan=tambah">
    <table>
        <tr>
            <td style="width:25pc;"><input type="text" class="form-control" required name="satuan"
                    placeholder="Masukan Satuan Barang Baru"></td>
            <td style="padding-left:10px;"><button id="tombol-simpan" class="btn btn-primary"><i class="fa fa-plus"></i>
                    Insert Data</button></td>
        </tr>
    </table>
</form>
<?php }?>
<br />
<div class="card card-body">
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm" id="example1">
            <thead>
                <tr style="background:#DFF0D8;color:#333;">
                    <th>No.</th>
                    <th>Satuan</th>
                    <th>Tanggal Input</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
				$hasil = $lihat -> satuan();
				$no=1;
				foreach($hasil as $isi){
			?>
                <tr>
                    <td><?php echo $no;?></td>
                    <td><?php echo $isi['nama_satuan'];?></td>
                    <td><?php echo $isi['tgl_input'];?></td>
                    <td>
                        <a href="index.php?page=satuan&uid=<?php echo $isi['id_satuan'];?>"><button
                                class="btn btn-warning">Edit</button></a>
                        <a href="fungsi/hapus/hapus.php?satuan=hapus&id=<?php echo $isi['id_satuan'];?>"
                            onclick="javascript:return confirm('Hapus Data Satuan ?');"><button
                                class="btn btn-danger">Hapus</button></a>
                    </td>
                </tr>
                <?php $no++; }?>
            </tbody>
        </table>
    </div>
</div>