<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Data Transaksi Barang Masuk
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<button type="button" class="btn btn-sm btn-primary" onclick="location.href=('/barangmasuk/index')">
    <i class="fa fa-plus-circle"></i> Input Transaksi
</button>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<?= form_open('barangmasuk/data') ?>
<?= "<span class=\"badge badge-success\">Total data: $totaldata</span> " ?>

<div class="input-group mb-3">
    <input type="text" class="form-control" placeholder="Cari berdasarkan faktur" name="cari" value="<?= $cari; ?>" autofocus="true">
    <div class="input-group-append">
        <button class="btn btn-outline-primary" type="submit" name="tombolCari">
            <i class="fa fa-search"></i>
        </button>
    </div>
</div>
<?= form_close(); ?>
<table class="table table-sm table-bordered">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Faktur</th>
            <th>Tanggal</th>
            <th>Jumlah Item</th>
            <th>Total Harga (Rp)</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1 + (($currentPage - 1) * 5);
        foreach ($tampildata as $row) : ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['faktur']; ?></td>
                <td><?= date('d-m-Y', strtotime($row['tglfaktur'])); ?></td>
                <td align="center">
                    <?php
                    $db = \Config\Database::connect();

                    $jumlahData = $db->table('detail_barangmasuk')->where('detfaktur', $row['faktur'])->countAllResults();
                    ?>
                    <span style="cursor: pointer; font-weight: bold; color: blue;" onclick="detailItem('<?= $row['faktur']; ?>')">
                        <?= $jumlahData; ?>
                    </span>
                </td>
                <td>
                    <?= number_format($row['totalharga'], 0, ",", ".") ?>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" title="Edit data" onclick="edit('<?= sha1($row['faktur']); ?>')">
                        <i class="fa fa-edit"></i>
                    </button>&nbsp;
                    <button type="button" class="btn btn-sm btn-danger" title="Hapus data" onclick="hapusTransaksi('<?= $row['faktur']; ?>')">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="viewmodal" style="display: none;"></div>
<div class="float-left mt-4">
    <?= $pager->links('barangmasuk', 'paging'); ?>
</div>
<script>
    //hapus transaksi
    function hapusTransaksi(faktur) {
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
                    url: "/barangmasuk/hapusTransaksi",
                    data: {
                        faktur: faktur
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
    //edit faktur
    function edit(faktur) {
        window.location = ('/barangmasuk/editbarangmasuk/') + faktur;
    }

    //menampilkan detail jumlah item
    function detailItem(faktur) {
        $.ajax({
            type: "post",
            url: "/barangmasuk/detailItem",
            data: {
                faktur: faktur
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodal').html(response.data).show();
                    $('#modalitem').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }

        });
    }
</script>
<?= $this->endSection('isi'); ?>