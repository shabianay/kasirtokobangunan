<?php

session_start();
if (!empty($_SESSION['admin'])) {
    require '../../config.php';
    if (!empty($_GET['kategori'])) {
        $nama = htmlentities(htmlentities($_POST['kategori']));
        $tgl = date("j F Y, G:i");
        $data[] = $nama;
        $data[] = $tgl;
        $sql = 'INSERT INTO kategori (nama_kategori,tgl_input) VALUES(?,?)';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=kategori&&success=tambah-data"</script>';
    }

    if (!empty($_GET['satuan'])) {
        $nama = htmlentities(htmlentities($_POST['satuan']));
        $tgl = date("j F Y, G:i");
        $data[] = $nama;
        $data[] = $tgl;
        $sql = 'INSERT INTO satuan (nama_satuan,tgl_input) VALUES(?,?)';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=satuan&&success=tambah-data"</script>';
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

        $data[] = $id;
        $data[] = $kategori;
        $data[] = $nama;
        $data[] = $merk;
        $data[] = $beli;
        $data[] = $jual;
        $data[] = $satuan;
        $data[] = $stok;
        $data[] = $tgl;
        $sql = 'INSERT INTO barang (id_barang,id_kategori,nama_barang,merk,harga_beli,harga_jual,satuan_barang,stok,tgl_input) 
			    VALUES (?,?,?,?,?,?,?,?,?) ';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=barang&success=tambah-data"</script>';
    }

    if (!empty($_GET['jual'])) {
        $id = $_GET['id'];

        // get tabel barang id_barang
        $sql = 'SELECT * FROM barang WHERE id_barang = ?';
        $row = $config->prepare($sql);
        $row->execute(array($id));
        $hsl = $row->fetch();

        if ($hsl['stok'] > 0) {
            $kasir =  $_GET['id_kasir'];
            $jumlah = 1;
            $total = $hsl['harga_jual'];
            $tgl = date("j F Y, G:i");
            $satuan_barang = $hsl['satuan_barang'];
            $harga_jual = $hsl['harga_jual'];

            $data1[] = $id;
            $data1[] = $kasir;
            $data1[] = $jumlah;
            $data1[] = $total;
            $data1[] = $tgl;
            $data1[] = $satuan_barang;
            $data1[] = $harga_jual;

            $sql1 = 'INSERT INTO penjualan (id_barang,id_member,jumlah,total,tanggal_input,satuan_barang,harga_jual) VALUES (?,?,?,?,?,?,?)';
            $row1 = $config->prepare($sql1);
            $row1->execute($data1);

            echo '<script>window.location="../../index.php?page=jual&success=tambah-data"</script>';
        } else {
            echo '<script>alert("Stok Barang Anda Telah Habis !");
					window.location="../../index.php?page=jual#keranjang"</script>';
        }
    }

    if (!empty($_GET['pembeli']) && $_GET['pembeli'] == 'tambah') {
        if (!empty($_POST['nama_pembeli']) && !empty($_POST['alamat_pembeli']) && !empty($_POST['telepon_pembeli'])) {
            $nama_pembeli = htmlentities($_POST['nama_pembeli']);
            $alamat_pembeli = htmlentities($_POST['alamat_pembeli']);
            $telepon_pembeli = htmlentities($_POST['telepon_pembeli']);

            $data[] = $nama_pembeli;
            $data[] = $alamat_pembeli;
            $data[] = $telepon_pembeli;
            $sql = 'INSERT INTO penjualan (nama_pembeli, alamat_pembeli, telepon_pembeli) VALUES (?, ?, ?)';
            $row = $config->prepare($sql);
            $row->execute($data);

            // Setelah data penjualan berhasil ditambahkan, lakukan redirect ke halaman yang sesuai
            echo '<script>window.location="../../index.php?page=jual&success=tambah-data"</script>';
        }
    }
}
