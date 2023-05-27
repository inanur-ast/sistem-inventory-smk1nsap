<table class="table table-sm table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode barang</th>
            <th>Nama barang</th>
            <th>Stok</th>
            <th>Satuan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($tampildata->getResultArray() as $row) :
        ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['brgkode'] ?></td>
                <td><?= $row['brgnama'] ?></td>
                <td><?= number_format($row['brgstok'], 0, ",", ".") ?></td>
                <td><?= $row['satnama'] ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" title="Pilih data" onclick="pilih('<?= $row['brgkode']; ?>')">
                        Pilih
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>

</script>