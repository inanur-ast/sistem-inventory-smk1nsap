<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Data Inventaris Ruang
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<button type="button" class="btn btn-sm btn-primary" onclick="location.href=('/inventarisRuang/index')">
    <i class="fa fa-plus-circle"></i> Input Data
</button>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<?= form_open('barangmasuk/data') ?>

<?= form_close(); ?>
<table class="table table-sm table-bordered">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Nama Ruangan</th>
            <th>Tanggal</th>
            <th>Jumlah Item</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($tampildata->getResultArray() as $row) : ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['id']; ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                <td align="center">
                    <?php
                    $db = \Config\Database::connect();

                    $jumlahData = $db->table('detail_inventarisruang')->where('detinven', $row['id'])->countAllResults();
                    ?>
                    <span style="cursor: pointer; font-weight: bold; color: blue;" onclick="detailItem('<?= $row['id']; ?>')">
                        <?= $jumlahData; ?>
                    </span>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" title="Edit data" onclick="edit('<?= sha1($row['id']); ?>')">
                        <i class="fa fa-edit"></i>
                    </button>&nbsp;
                    <button type="button" class="btn btn-sm btn-danger" title="Hapus data" onclick="hapusTransaksi('<?= $row['id']; ?>')">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="viewmodal" style="display: none;"></div>


<script>
    //menampilkan detail jumlah item
    function detailItem(id) {
        $.ajax({
            type: "post",
            url: "/inventarisRuang/detailItem",
            data: {
                id: id
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodal').html(response.data).show();
                    $('#modalitemruang').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }

        });
    }

    //edit faktur
    function edit(id) {
        window.location = ('/inventarisRuang/editinventarisruang/') + id;
    }
    //hapus transaksi
    function hapusTransaksi(id) {
        Swal.fire({
            title: 'Hapus Transaksi?',
            text: "Yakin menghapus transaksi ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "/inventarisRuang/hapusTransaksi",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.sukses,
                            }).then((result) => {
                                window.location.reload();
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
<?= $this->endSection('isi'); ?>