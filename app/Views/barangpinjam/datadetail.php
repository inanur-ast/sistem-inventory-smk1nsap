<table class="table table-sm table-striped table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>No Peminjaman</th>
            <th>Kode barang</th>
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
                <td><?= $row['detpeminjam']; ?></td>
                <td><?= $row['brgkode']; ?></td>
                <td><?= $row['brgnama']; ?></td>
                <td><?= $row['detkatid']; ?></td>
                <td><?= number_format($row['detjumlah'], 0, ",", ".") ?></td>
                <td><?= $row['konnama']; ?></td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusItem(<?= $row['id']; ?>)">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info" title="Edit data" onclick="editItem('<?= $row['id'] ?>')">
                        <i class="fa fa-edit"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>
<script>
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
                    url: "/barangpinjam/hapusDetail",
                    data: {
                        id: id,

                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            DataDetail();
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

    function editItem(id) {
        $('#id').val(id);
        $.ajax({
            type: "post",
            url: "/barangpinjam/editItem",
            data: {
                id: $('#id').val()
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

                    $('#tombolSimpanItem').fadeIn();
                    $('#tombolTambahItem').fadeOut();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }
</script>