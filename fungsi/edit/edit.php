<?php
session_start();
if (!empty($_SESSION['admin'])) {
    require '../../config.php';
    if (!empty($_GET['pengaturan'])) {
        $nama = htmlentities($_POST['namatoko']);
        $alamat = htmlentities($_POST['alamat']);
        $kontak = htmlentities($_POST['kontak']);
        $pemilik = htmlentities($_POST['pemilik']);
        $id = '1';

        $data[] = $nama;
        $data[] = $alamat;
        $data[] = $kontak;
        $data[] = $pemilik;
        $data[] = $id;
        $sql = 'UPDATE toko SET nama_toko=?, alamat_toko=?, tlp=?, nama_pemilik=? WHERE id_toko = ?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=pengaturan&success=edit-data"</script>';
    }

    if (!empty($_GET['kategori'])) {
        $nama = htmlentities($_POST['kategori']);
        $id = htmlentities($_POST['id']);
        $data[] = $nama;
        $data[] = $id;
        $sql = 'UPDATE kategori SET  nama_kategori=? WHERE id_kategori=?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=kategori&uid=' . $id . '&success-edit=edit-data"</script>';
    }

    if (!empty($_GET['satuan'])) {
        $nama = htmlentities($_POST['satuan']);
        $id = htmlentities($_POST['id']);
        $data[] = $nama;
        $data[] = $id;
        $sql = 'UPDATE satuan SET  nama_satuan=? WHERE id_satuan=?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=satuan&uid=' . $id . '&success-edit=edit-data"</script>';
    }

    if (!empty($_GET['stok'])) {
        $restok = htmlentities($_POST['restok']);
        $id = htmlentities($_POST['id']);
        $dataS[] = $id;
        $sqlS = 'select*from barang WHERE id_barang=?';
        $rowS = $config->prepare($sqlS);
        $rowS->execute($dataS);
        $hasil = $rowS->fetch();

        $stok = $restok + $hasil['stok'];

        $data[] = $stok;
        $data[] = $id;
        $sql = 'UPDATE barang SET stok=? WHERE id_barang=?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=barang&success-stok=stok-data"</script>';
    }

    if (!empty($_GET['barang'])) {
        $id = htmlentities($_POST['id']);
        $kategori = htmlentities($_POST['kategori']);
        $nama = htmlentities($_POST['nama']);
        $merk = htmlentities($_POST['merk']);
        $beli = htmlentities($_POST['beli']);
        $jual = htmlentities($_POST['jual']);
        $satuan = htmlentities($_POST['satuan']);
        $stok = htmlentities($_POST['stok']);
        $tgl = htmlentities($_POST['tgl']);

        $data[] = $kategori;
        $data[] = $nama;
        $data[] = $merk;
        $data[] = $beli;
        $data[] = $jual;
        $data[] = $satuan;
        $data[] = $stok;
        $data[] = $tgl;
        $data[] = $id;
        $sql = 'UPDATE barang SET id_kategori=?, nama_barang=?, merk=?, 
				harga_beli=?, harga_jual=?, satuan_barang=?, stok=?, tgl_update=?  WHERE id_barang=?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=barang/edit&barang=' . $id . '&success=edit-data"</script>';
    }

    if (!empty($_GET['gambar'])) {
        $id = htmlentities($_POST['id']);
        set_time_limit(0);
        $allowedImageType = array("image/gif", "image/JPG", "image/jpeg", "image/pjpeg", "image/png", "image/x-png", 'image/webp');
        $filepath = $_FILES['foto']['tmp_name'];
        $fileSize = filesize($filepath);
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $filetype = finfo_file($fileinfo, $filepath);
        $allowedTypes = [
            'image/png'   => 'png',
            'image/jpeg'  => 'jpg',
            'image/gif'   => 'gif',
            'image/jpg'   => 'jpeg',
            'image/webp'  => 'webp'
        ];
        if (!in_array($filetype, array_keys($allowedTypes))) {
            echo '<script>alert("You can only upload JPG, PNG and GIF file");window.location="../../index.php?page=user"</script>';
            exit;
        } else if ($_FILES['foto']["error"] > 0) {
            echo '<script>alert("You can only upload JPG, PNG and GIF file");window.location="../../index.php?page=user"</script>';
            exit;
        } elseif (!in_array($_FILES['foto']["type"], $allowedImageType)) {
            // echo "You can only upload JPG, PNG and GIF file";
            // echo "<font face='Verdana' size='2' ><BR><BR><BR>
            // 		<a href='../../index.php?page=user'>Back to upform</a><BR>";
            echo '<script>alert("You can only upload JPG, PNG and GIF file");window.location="../../index.php?page=user"</script>';
            exit;
        } elseif (round($_FILES['foto']["size"] / 1024) > 4096) {
            // echo "WARNING !!! Besar Gambar Tidak Boleh Lebih Dari 4 MB";
            // echo "<font face='Verdana' size='2' ><BR><BR><BR>
            // 		<a href='../../index.php?page=user'>Back to upform</a><BR>";
            echo '<script>alert("WARNING !!! Besar Gambar Tidak Boleh Lebih Dari 4 MB");window.location="../../index.php?page=user"</script>';
            exit;
        } else {
            $dir = '../../assets/img/user/';
            $tmp_name = $_FILES['foto']['tmp_name'];
            $name = time() . basename($_FILES['foto']['name']);
            if (move_uploaded_file($tmp_name, $dir . $name)) {
                //post foto lama
                $foto2 = $_POST['foto2'];
                //remove foto di direktori
                unlink('../../assets/img/user/' . $foto2 . '');
                //input foto
                $id = $_POST['id'];
                $data[] = $name;
                $data[] = $id;
                $sql = 'UPDATE member SET gambar=?  WHERE member.id_member=?';
                $row = $config->prepare($sql);
                $row->execute($data);
                echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
            } else {
                echo '<script>alert("Masukan Gambar !");window.location="../../index.php?page=user"</script>';
                exit;
            }
        }
    }

    if (!empty($_GET['profil'])) {
        $id = htmlentities($_POST['id']);
        $nama = htmlentities($_POST['nama']);
        $alamat = htmlentities($_POST['alamat']);
        $tlp = htmlentities($_POST['tlp']);
        $email = htmlentities($_POST['email']);
        $nik = htmlentities($_POST['nik']);

        $data[] = $nama;
        $data[] = $alamat;
        $data[] = $tlp;
        $data[] = $email;
        $data[] = $nik;
        $data[] = $id;
        $sql = 'UPDATE member SET nm_member=?,alamat_member=?,telepon=?,email=?,NIK=? WHERE id_member=?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
    }

    if (!empty($_GET['pass'])) {
        $id = htmlentities($_POST['id']);
        $user = htmlentities($_POST['user']);
        $pass = htmlentities($_POST['pass']);

        $data[] = $user;
        $data[] = $pass;
        $data[] = $id;
        $sql = 'UPDATE login SET user=?,pass=md5(?) WHERE id_member=?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
    }

    if (!empty($_GET['laporan'])) {
        $id = htmlentities($_POST['id']);
        $jumlah = htmlentities($_POST['jumlah']);
        $harga_jual = htmlentities($_POST['jual']);

        // Hitung total berdasarkan jumlah dan harga jual
        $total = $jumlah * $harga_jual;

        // Persiapkan array data untuk query
        $data = array($jumlah, $total, $harga_jual, $id);

        // Query SQL untuk update data
        $sql = 'UPDATE nota SET jumlah=?, total=?, harga_jual=? WHERE id_nota=?';

        // Persiapkan dan jalankan pernyataan SQL
        $row = $config->prepare($sql);
        $row->execute($data);

        // Redirect ke halaman setelah update berhasil
        echo '<script>window.location="../../index.php?page=laporan&success=edit-data"</script>';
    }

    if (!empty($_GET['jual'])) {
        $id = $_POST['id'];
        $id_barang = $_POST['id_barang'];
        $jumlah = $_POST['jumlah'];
        $harga_jual = $_POST['harga_jual'];

        $sql_tampil = "SELECT * FROM barang WHERE id_barang=?";
        $row_tampil = $config->prepare($sql_tampil);
        $row_tampil->execute(array($id_barang));
        $hasil = $row_tampil->fetch();

        if ($hasil['stok'] >= $jumlah) {
            $total = $harga_jual * $jumlah;
            $data1 = array($jumlah, $total, $harga_jual, $id);
            $sql1 = 'UPDATE penjualan SET jumlah=?, total=?, harga_jual=? WHERE id_penjualan=?';
            $row1 = $config->prepare($sql1);
            $row1->execute($data1);
            echo '<script>window.location="../../index.php?page=jual#keranjang"</script>';
        } else {
            echo '<script>alert("Keranjang Melebihi Stok Barang Anda !");
					window.location="../../index.php?page=jual#keranjang"</script>';
        }
    }

    if (!empty($_GET['cari_barang'])) {
        $cari = trim(strip_tags($_POST['keyword']));
        if ($cari == '') {
        } else {
            $sql = "SELECT barang.*, kategori.id_kategori, kategori.nama_kategori
                FROM barang 
                INNER JOIN kategori ON barang.id_kategori = kategori.id_kategori
                WHERE barang.id_barang LIKE '%$cari%' OR barang.nama_barang LIKE '%$cari%' OR barang.merk LIKE '%$cari%'";
            $row = $config->prepare($sql);
            $row->execute();
            $hasil1 = $row->fetchAll();
?>
            <table class="table table-stripped" width="100%" id="example2">
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Merk</th>
                    <th>Satuan</th>
                    <th>Harga Jual</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($hasil1 as $hasil) { ?>
                    <tr>
                        <td><?php echo $hasil['id_barang']; ?></td>
                        <td><?php echo $hasil['nama_barang']; ?></td>
                        <td><?php echo $hasil['merk']; ?></td>
                        <td><?php echo $hasil['satuan_barang']; ?></td>
                        <td><?php echo $hasil['harga_jual']; ?></td>
                        <td>
                            <a href="fungsi/tambah/tambah.php?jual=jual&id=<?php echo $hasil['id_barang']; ?>&id_kasir=<?php echo $_SESSION['admin']['id_member']; ?>" class="btn btn-success">
                                <i class="fa fa-shopping-cart"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php
        }
    }

    if (!empty($_GET['cari_pembeli'])) {
        $cari = trim(strip_tags($_POST['keyword']));
        if ($cari == '') {
            // Tampilkan pesan error jika keyword kosong
            echo "Keyword pencarian tidak boleh kosong";
        } else {
            // Lakukan query untuk mencari data pembeli berdasarkan nama_pembeli
            $sql = "SELECT * FROM nota WHERE nama_pembeli LIKE '%$cari%' OR alamat_pembeli LIKE '%$cari%' OR telepon_pembeli LIKE '%$cari%' GROUP BY nama_pembeli";
            $result = $config->prepare($sql);
            $result->execute();
            $hasil = $result->fetchAll();
        ?>
            <table class="table table-stripped" width="100%" id="example2">
                <tr>
                    <th>Nama Pembeli</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($hasil as $data) { ?>
                    <tr>
                        <td><?php echo $data['nama_pembeli']; ?></td>
                        <td><?php echo $data['alamat_pembeli']; ?></td>
                        <td><?php echo $data['telepon_pembeli']; ?></td>
                        <td>
                            <a href="javascript:void(0);" class="btn btn-primary" onclick="tambahPembeli(<?php echo $data['id_nota']; ?>)">
                                <i class="fa fa-plus"></i> Tambah Pembeli
                            </a>
                            <a href="fungsi/hapus/hapus.php?pembeli=hapus&id=<?php echo $data['id_nota']; ?>" onclick="javascript:return confirm('Yakin Hapus Data ?');"><button class="btn btn-danger">Hapus</button></a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
<?php
        }
    }

    if (isset($_GET['id_nota'])) {
        $id_nota = $_GET['id_nota'];

        // Lakukan query untuk mendapatkan data pembeli berdasarkan id_nota
        $sql = "SELECT * FROM nota WHERE id_nota = ?";
        $stmt = $config->prepare($sql);
        $stmt->execute([$id_nota]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Mengembalikan data dalam format JSON
        echo json_encode($data);
    }
}
