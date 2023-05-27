<table class="table table-sm table-striped table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Ruang Inventaris </th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Kondisi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($datatemp->getResultArray() as $row) : ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['detinven']; ?></td>
                <td><?= $row['brgnama']; ?></td>
                <td><?= $row['detkatid']; ?></td>
                <td><?= number_format($row['detjml'], 0, ",", ".") ?></td>
                <td><?= $row['konnama']; ?></td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusItem(<?= $row['iddetail']; ?>)">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>
<script>
    function hapusItem(iddetail) {
        Swal.fire({
            title: 'Hapus Item?',
            text: "Yakin menghapus item ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "/inventarisRuang/hapus",
                    data: {
                        iddetail: iddetail
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            DataTemp();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.sukses,
                            })
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + '\n' + thrownError);
                    }

                });
            }
        })
    }
</script>