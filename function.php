<?php
session_start();

//Membuat koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "stockbarang2");

//Menambah barang baru
if (isset($_POST['addnewbarang'])) {
    $idbarang = $_POST['idbarang'];
    $namabarang = $_POST['namabarang'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn, "insert into stock (idbarang, namabarang, stock) values('$idbarang','$namabarang','$stock')");
    if ($addtotable) {
        echo "sukses";
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
};


//Menambah barang masuk
if (isset($_POST['barangmasuk'])) {
    $barangnya = $_POST['barangnya'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang + $qty;

    $addtomasuk = mysqli_query($conn, "insert into masuk (idbarang, qty) values ('$barangnya','$qty')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
    if ($addtomasuk && $updatestockmasuk) {
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}

//Menambah barang keluar
if (isset($_POST['addbarangkeluar'])) {
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang - $qty;

    $addtokeluar = mysqli_query($conn, "insert into keluar (idbarang, penerima, qty) values ('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
    if ($addtokeluar && $updatestockmasuk) {
        header('location:keluar.php');
    } else {
        echo 'Gagal';
        header('location:keluar.php');
    }
}


//update info barang
if (isset($_POST['updatebarang'])) {
    $idbarang = $_POST['idbarang'];
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $stock = $_POST['stock'];

    $update = mysqli_query($conn, "update stock set idbarang='$idbarang', namabarang='$namabarang', stock='$stock' where idbarang='$idb'");
    if ($update) {
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}


//menghapus barang dari stock
if (isset($_POST['hapusbarang'])) {
    $idb = $_POST['idb'];
    $hapus = mysqli_query($conn, "delete from stock where idbarang='$idb'");
    if ($hapus) {
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
}



//edit barang barang masuk
if (isset($_POST['updatebarangmasuk'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stocksekarang = $stocknya['stock'];

    $qtysekarang = mysqli_query($conn, "select * from masuk where idbarang='$idb'");
    $qtynya = mysqli_fetch_array($qtysekarang);
    $qtysekarang = $qtynya['qty'];

    if ($qty > $qtysekarang) {
        $selisih = $qty - $qtysekarang;
        $kurang = $stocksekarang + $selisih;
        $kurangistock = mysqli_query($conn, "update stock set stock='$kurang' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty', idbarang='$idb' where idbarang='$idb'");

        if ($kurangistock && $updatenya) {
            header('location:masuk.php');
        } else {
            echo 'gagal';
            header('location:masuk.php');
        }
    } else {
        $selisih = $qtysekarang - $qty;
        $kurang = $stocksekarang - $selisih;
        $kurangistock = mysqli_query($conn, "update stock set stock='$kurang' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty', idbarang='$idb' where idbarang='$idb'");

        if ($kurangistock && $updatenya) {
            header('location:masuk.php');
        } else {
            echo 'gagal';
            header('location:masuk.php');
        }
    }
}


//menghapus barang masuk
if (isset($_POST['hapusbarangmasuk'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok - $qty;

    $update = mysqli_query($conn, "update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "delete from masuk where idbarang='$idb'");
    if ($update && $hapusdata) {
        header('location:masuk.php');
    } else {
        header('location:masuk.php');
    }
}

//edit barang barang keluar
if (isset($_POST['updatebarangkeluar'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];
    $penerima = $_POST['penerima'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stocksekarang = $stocknya['stock'];

    $qtysekarang = mysqli_query($conn, "select * from keluar where idbarang='$idb'");
    $qtynya = mysqli_fetch_array($qtysekarang);
    $qtysekarang = $qtynya['qty'];

    if ($qty > $qtysekarang) {
        $selisih = $qty - $qtysekarang;
        $kurang = $stocksekarang - $selisih;
        $kurangistock = mysqli_query($conn, "update stock set stock='$kurang' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set penerima='$penerima', idbarang='$idb', qty='$qty' where idbarang='$idb'");

        if ($kurangistock && $updatenya) {
            header('location:keluar.php');
        } else {
            echo 'gagal';
            header('location:keluar.php');
        }
    } else {
        $selisih = $qtysekarang - $qty;
        $kurang = $stocksekarang + $selisih;
        $kurangistock = mysqli_query($conn, "update stock set stock='$kurang' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set penerima='$penerima', idbarang='$idb', qty='$qty' where idbarang='$idb'");

        if ($kurangistock && $updatenya) {
            header('location:keluar.php');
        } else {
            echo 'gagal';
            header('location:keluar.php');
        }
    }
}


//menghapus barang keluar
if (isset($_POST['hapusbarangkeluar'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok + $qty;

    $update = mysqli_query($conn, "update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "delete from keluar where idbarang='$idb'");
    if ($update && $hapusdata) {
        header('location:keluar.php');
    } else {
        header('location:keluar.php');
    }
}
