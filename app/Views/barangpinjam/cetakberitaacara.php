<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Pinjam</title>
</head>

<body onload="window.print();">
    <table style="width: 100%; border-collapse: collapse; text-align:center;" border="1">
        <tr>
            <td>
                <table style="width: 100%; text-align:center" border="0">
                    <tr style="text-align: center">
                        <td>
                            <h1> Sistem Inventory Barang</h1>
                            <h2> Lab TKJ SMKN 1 Saptosari</h2>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table style="width: 100%; text-align:center;" border="0">
                    <tr style="text-align: center">
                        <td>
                            <h2>Berita Acara</h2>
                            <h3><u>Laporan Pengembalian Barang</u></h3><br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <br>
                            <center>
                                <table border="1" cellpadding="5" style="border-collapse: collapse; border: 1px solid #000; text-align: center; width:80%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No.Peminjaman</th>
                                            <th>ID Siswa</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $nomor = 1;
                                        foreach ($dataLaporan as $row) : ?>
                                            <tr>
                                                <td><?= $nomor++; ?>
                                                <td><?= $row['id_peminjam']; ?></td>
                                                <td><?= $row['idsiswa']; ?></td>
                                                <td><?= $row['tglpinjam']; ?></td>
                                                <td><?= $row['tglkembali']; ?></td>
                                                <td><?= $row['status']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            </center>
                        </td>
                    </tr>
                    </thead>
                    <tr style="text-align: center">
                        <td>
                            <h3><u>Rincian Barang Pinjam</u></h3>
                        </td>
                    </tr>
                </table>
                <center>
                    <table border="1" cellpadding="5" style="border-collapse: collapse; border: 1px solid #000; text-align: center; width:80%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Peminjaman</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Kondisi</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nomor = 1;
                            foreach ($datadetail as $row) : ?>
                                <tr>
                                    <td><?= $nomor++; ?>
                                        <input type="hidden" value="<?= $row['id']; ?>" id="id">
                                    </td>
                                    <td><?= $row['detpeminjam']; ?></td>
                                    <td><?= $row['detkdbrg']; ?></td>
                                    <td><?= $row['detkatid']; ?></td>
                                    <td><?= $row['detkondisi']; ?></td>
                                    <td><?= number_format($row['detjumlah'], 0, ",", ".") ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </center>
            </td>
        </tr>
        </thead>
    </table>
</body>

</html>