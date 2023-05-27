<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang</title>
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
                            <h3><u>Laporan Barang</u></h3><br>
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
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Kategori</th>
                                            <th>Satuan</th>
                                            <th>Stok</th>
                                            <th>Spesifikasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $nomor = 1;
                                        foreach ($dataLaporan->getResultArray() as $row) : ?>
                                            <tr>
                                                <td><?= $nomor++; ?>
                                                <td><?= $row['brgkode']; ?></td>
                                                <td><?= $row['brgnama']; ?></td>
                                                <td><?= $row['katnama']; ?></td>
                                                <td><?= $row['satnama']; ?></td>
                                                <td><?= $row['brgstok']; ?></td>
                                                <td><?= $row['spesifikasi']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </center>
                        </td>
                    </tr>
                </table>
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