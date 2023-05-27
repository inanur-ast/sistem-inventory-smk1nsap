<div class="modal fade" id="modalitemruang" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Detail Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama barang</th>
                            <th>Kondisi Barang</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nomor = 1;
                        foreach ($tampildatadetail->getResultArray() as $row) : ?>
                            <tr>
                                <td><?= $nomor++; ?></td>
                                <td><?= $row['detbrgkode']; ?></td>
                                <td><?= $row['brgnama']; ?></td>
                                <td><?= $row['konnama']; ?></td>
                                <td style="text-align: center;"><?= number_format($row['detjml'], 0, ",", ".") ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>