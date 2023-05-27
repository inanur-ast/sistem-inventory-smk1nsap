<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Manajemen Data Peminjaman Barang
<?= $this->endSection('judul'); ?>


<?= $this->section('isi'); ?>
<?= session()->getFlashdata('error'); ?>
<?= session()->getFlashdata('sukses'); ?>
<?= form_open('datapeminjamanbarang/index') ?>
<div class="input-group mb-3">
    <input type="text" class="form-control" placeholder="Cari data barang" aria-label="Recipient's username" aria-describedby="button-addon2" name="caripeminjamanbarang">
    <button class=" btn btn-outline-primary" type="submit" id="tombolcaripembarang" name="tombolcaripembarang">
        <i class="fa fa-search"></i>
    </button>
</div>
<?= form_close(); ?>
<table class="table table-striped table-bordered" style="width:100%;">
    <thead class="table-dark">
        <tr>
            <th style="width: 10%;">No</th>
            <th>No peminjaman</th>
            <th>Kode barang</th>
            <th>Jumlah barang</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($tampildata as $r) : ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $r['detpeminjam']; ?></td>
                <td><?= $r['detkdbrg']; ?></td>
                <td><?= number_format($r['detjumlah']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="float-center">

</div>

<?= $this->endSection('isi'); ?>