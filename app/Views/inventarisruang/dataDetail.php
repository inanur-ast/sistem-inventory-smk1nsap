<table class="table table-sm table-striped table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
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
        foreach ($datadetail->getResultArray() as $row) : ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['detbrgkode']; ?></td>
                <td><?= $row['brgnama']; ?></td>
                <td><?= $row['detkatid']; ?></td>
                <td><?= number_format($row['detjml'], 0, ",", ".") ?></td>
                <td><?= $row['konnama']; ?></td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusItem('<?= $row['iddetail'] ?>')">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info" title="Edit data" onclick="editItem('<?= $row['iddetail'] ?>')">
                        <i class="fa fa-edit"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    function editItem(id) {
        $('#iddetail').val(id);
        $.ajax({
            type: "post",
            url: "/inventarisRuang/editItem",
            data: {
                iddetail: $('#iddetail').val()
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    let data = response.sukses;
                    $('#kdbrg').val(data.kodebarang);
                    $('#namabrg').val(data.namabarang);
                    $('#kategori').val(data.kategori);
                    $('#jumlah').val(data.jumlah);
                    $('#kondisi').val(data.kondisi);

                    $('#tombolEditItem').fadeIn();
                    $('#tombolReload').fadeIn();
                    $('#tombolTambahItem').fadeOut();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    function hapusItem(id) {
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
                    url: "/inventarisRuang/hapusItemDetail",
                    data: {
                        id: id,
                        inventaris: $('#inventaris').val()
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            dataDetail();
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