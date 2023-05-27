<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Ruang</title>
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
                            <h3><u>Laporan Inventaris Ruang</u></h3><br>
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
                                            <th>Ruangan</th>
                                            <th>Tanggal</th>
                                            <th>Total Barang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $nomor = 1;
                                        foreach ($dataLaporan->getResultArray() as $row) : ?>
                                            <tr>
                                                <td><?= $nomor++; ?>
                                                <td><?= $row['id']; ?></td>
                                                <td><?= $row['tanggal']; ?></td>
                                                <td><?= $row['totalbarang']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </center>
                        </td>
                    </tr>
                    </thead>

                    <tr style="text-align: center">
                        <td>
                            <h3><u>Rincian Barang</u></h3>
                        </td>
                    </tr>
                </table>
                <center>
                    <table border="1" cellpadding="5" style="border-collapse: collapse; border: 1px solid #000; text-align: center; width:80%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Ruangan</th>
                                <th>Kode Barang</th>
                                <th>Nama barang</th>
                                <th>Kategori</th>
                                <th>Kondisi</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nomor = 1;
                            $totalSeluruhDetail = 0;
                            foreach ($dataDetailLaporan->getResultArray() as $r) :
                                $totalSeluruhDetail += $r['detjml']; ?>
                                <tr>
                                    <td><?= $nomor++; ?></td>
                                    <td><?= $r['detinven']; ?></td>
                                    <td><?= $r['detbrgkode']; ?></td>
                                    <td><?= $r['brgnama']; ?></td>
                                    <td><?= $r['detkatid']; ?></td>
                                    <td><?= $r['konnama']; ?></td>
                                    <td><?= number_format($r['detjml'], 0, ",", ".") ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" style="text-align: left;">Total Seluruh Barang</th>
                                <td><?= number_format($totalSeluruhDetail); ?></td>
                            </tr>
                            <tr>
                                <th colspan="7" style="text-align: left;">Kondisi Barang</th>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: left;">Baik</th>
                                <td>
                                    <?php
                                    $db = \Config\Database::connect();
                                    $kondisibaik =  $db->table('detail_inventarisruang')->where('detkondisi', '1')->countAllResults();
                                    ?>
                                    <?= $kondisibaik; ?>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: left;">Rusak Ringan</th>
                                <td>
                                    <?php
                                    $db = \Config\Database::connect();
                                    $kondisirr =  $db->table('detail_inventarisruang')->where('detkondisi', '2')->countAllResults();
                                    ?>
                                    <?= $kondisirr; ?>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="6" style="text-align: left;">Rusak Berat</th>
                                <td>
                                    <?php
                                    $db = \Config\Database::connect();
                                    $kondisirb =  $db->table('detail_inventarisruang')->where('detkondisi', '3')->countAllResults();
                                    ?>
                                    <?= $kondisirb; ?>
                                </td>
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