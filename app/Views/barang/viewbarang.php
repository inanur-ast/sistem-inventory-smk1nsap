<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Manajemen Data Barang
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-plus-circle"></i> Tambah Data', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('barang/formtambah') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<?= session()->getFlashdata('error'); ?>
<?= session()->getFlashdata('sukses'); ?>
<?= form_open('barang/index') ?>
<div class="input-group mb-3">
    <input type="text" class="form-control" placeholder="Cari data barang" aria-label="Recipient's username" aria-describedby="button-addon2" name="cari" value="<?= $cari; ?>">
    <button class=" btn btn-outline-primary" type="submit" id="tombolcari" name="tombolcari">
        <i class="fa fa-search"></i>
    </button>
</div>
<?= form_close(); ?>
<table class="table table-striped table-bordered" style="width:100%;">
    <thead class="table-dark">
        <tr>
            <th style="width: 10%;">No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Satuan</th>
            <th>Stok</th>
            <th>Spesifikasi</th>
            <th style="width: 15%;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1 + (($currentPage - 1) * 5);
        foreach ($tampildata as $row) : ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['brgkode']; ?></td>
                <td><?= $row['brgnama']; ?></td>
                <td><?= $row['katnama']; ?></td>
                <td><?= $row['satnama']; ?></td>
                <td><?= number_format($row['brgstok']); ?></td>
                <td><?= $row['spesifikasi']; ?></td>
                <td>
                    <button type="submit" class="btn btn-danger btnHapus"><i class="fa fa-trash-alt"></i></button>
                    <button type="submit" class="btn btn-info btnSimpan"><i class="fa fa-edit"></i></button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="float-center">
    <?= $pager->links('barang', 'paging'); ?>
</div>

<script>
    function edit(kode) {
        window.location = ('/barang/formedit/' + kode);
    }

    function hapus(kode) {
        pesan = confirm('Yakin data barang dihapus?');
        if (pesan) {
            window.location = ('/barang/hapus/' + kode);
        } else {
            return false;
        }
    }
</script>

<?= $this->endSection('isi'); ?>