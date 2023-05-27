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
                            <p>
                                Alamat : JL. Wonosari-Panggang, Km 22, Saptosari, Gunung Kidul, <br>
                                Kepek, Kec. Saptosari, Kabupaten Gunung Kidul, Daerah Istimewa Yogyakarta 55871</p>
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
                            <h3><u>Laporan Barang Pinjam</u></h3><br>
                            Periode : <?= $tgl_awal . " s/d "  . $tgl_akhir; ?>
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
                                            <th>Nama Siswa</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $nomor = 1;
                                        foreach ($dataLaporan->getResultArray() as $row) : ?>
                                            <tr>
                                                <td><?= $nomor++; ?>
                                                <td><?= $row['id_peminjam']; ?></td>
                                                <td><?= $row['idsiswa']; ?></td>
                                                <td><?= $row['namasiswa']; ?></td>
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
                                <th>No.Peminjaman</th>
                                <th>ID siswa</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Kondisi</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nomor = 1;
                            foreach ($dataDetailLaporan->getResultArray() as $r) : ?>
                                <tr>
                                    <td><?= $nomor++; ?></td>
                                    <td><?= $r['detpeminjam']; ?></td>
                                    <td><?= $r['idsiswa']; ?></td>
                                    <td><?= $r['detkdbrg']; ?></td>
                                    <td><?= $r['brgnama']; ?></td>
                                    <td><?= $r['tglpinjam']; ?></td>
                                    <td><?= $r['tglkembali']; ?></td>
                                    <td><?= $r['konnama']; ?></td>
                                    <td style="text-align: center;"><?= number_format($r['detjumlah'], 0, ",", ".") ?></td>
                                    <td><?= $r['status']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </center>
            </td>
        </tr>
    </table>
    <right>
        <p>
            <b> Saptosari, <br>
                Kepala Lab </b>
            <br>
            <br>
            <br>
            <br>
            <br>
            Wahyu Eko Supriyadi, S.Kom
        </p>
    </right>
</body>

</html>