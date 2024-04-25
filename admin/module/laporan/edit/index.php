 <?php
    $id = $_GET['laporan'];
    $hasil = $lihat->laporan_edit($id);
    ?>
 <a href="index.php?page=laporan" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Balik </a>
 <h4>Edit Laporan</h4>
 <?php if (isset($_GET['success'])) { ?>
     <div class="alert alert-success">
         <p>Edit Data Berhasil !</p>
     </div>
 <?php } ?>
 <?php if (isset($_GET['remove'])) { ?>
     <div class="alert alert-danger">
         <p>Hapus Data Berhasil !</p>
     </div>
 <?php } ?>
 <div class="card card-body">
     <div class="table-responsive">
         <table class="table table-striped">
             <form action="fungsi/edit/edit.php?laporan=edit" method="POST">
                 <tr>
                     <td>ID NOTA</td>
                     <td><input type="text" readonly="readonly" class="form-control" value="<?php echo $hasil['id_nota']; ?>" name="id"></td>
                 </tr>
                 <tr>
                     <td>Jumlah</td>
                     <td><input type="text" class="form-control" value="<?php echo $hasil['jumlah']; ?>" name="jumlah"></td>
                 </tr>
                 <tr>
                     <td>Harga Jual</td>
                     <td><input type="text" class="form-control" value="<?php echo $hasil['harga_jual']; ?>" name="jual"></td>
                 </tr>
                 <td></td>
                 <td><button class="btn btn-primary"><i class="fa fa-edit"></i> Update Data</button></td>
                 </tr>
             </form>
         </table>
     </div>
 </div>
