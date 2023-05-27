<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Masuk</title>
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
                            <h3><u>Laporan Barang Masuk</u></h3><br>
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
                                            <th>No.Faktur</th>
                                            <th>Tanggal</th>
                                            <th>Total Harga (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $nomor = 1;
                                        $totalSeluruhHarga = 0;
                                        foreach ($dataLaporan->getResultArray() as $row) :
                                            $totalSeluruhHarga += $row['totalharga']; ?>
                                            <tr>
                                                <td><?= $nomor++; ?>
                                                <td><?= $row['faktur']; ?></td>
                                                <td><?= $row['tglfaktur']; ?></td>
                                                <td style="text-align: right;">
                                                    <?= number_format($row['totalharga'], 0, ",", ".") ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Total Seluruh Barang</th>
                                            <td><?= number_format($totalSeluruhHarga); ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </center>
                        </td>
                    </tr>
                    </thead>

                    <tr style="text-align: center">
                        <td>
                            <h3><u>Rincian Barang Masuk</u></h3>
                        </td>
                    </tr>
                </table>
                <center>
                    <table border="1" cellpadding="5" style="border-collapse: collapse; border: 1px solid #000; text-align: center; width:80%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No.Faktur</th>
                                <th>Kode Barang</th>
                                <th>Nama barang</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nomor = 1;
                            $totalSeluruhDetailHarga = 0;
                            foreach ($dataDetailLaporan->getResultArray() as $r) :
                                $totalSeluruhDetailHarga += $r['detsubtotal']; ?>
                                <tr>
                                    <td><?= $nomor++; ?></td>
                                    <td><?= $r['detfaktur']; ?></td>
                                    <td><?= $r['detbrgkode']; ?></td>
                                    <td><?= $r['brgnama']; ?></td>
                                    <td style="text-align: right;"><?= number_format($r['detharga'], 0, ",", ".") ?></td>
                                    <td style="text-align: center;"><?= number_format($r['detjml'], 0, ",", ".") ?></td>
                                    <td style="text-align: right;"><?= number_format($r['detsubtotal'], 0, ",", ".") ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6">Total Seluruh Barang</th>
                                <td><?= number_format($totalSeluruhDetailHarga); ?></td>
                            </tr>
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