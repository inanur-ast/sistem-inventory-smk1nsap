<table class="table table-sm table-striped table-hover" id="datadetail">
    <thead>
        <tr>
            <th>No</th>
            <th>No Peminjaman</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Kondisi</th>
            <th>Jumlah</th>
            <th id="aksi" style="display: none;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($datadetail->getResultArray() as $row) : ?>
            <tr>
                <td><?= $nomor++; ?>
                    <input type="hidden" value="<?= $row['id']; ?>" id="id">
                </td>
                <td><?= $row['detpeminjam']; ?></td>
                <td><?= $row['brgnama']; ?></td>
                <td><?= $row['detkatid']; ?></td>
                <td><?= $row['konnama']; ?></td>
                <td><?= number_format($row['detjumlah'], 0, ",", ".") ?></td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>