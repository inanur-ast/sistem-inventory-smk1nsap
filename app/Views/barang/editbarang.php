<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Edit Data barang
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-backward"></i> Kembali', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('barang/index') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<?= form_open('barang/updatedata') ?>
<?= session()->getFlashdata('error'); ?>
<?= session()->getFlashdata('sukses'); ?>

<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Kode Barang</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="kodebrg" name="kodebrg" value="<?= $kodebrg; ?>" readonly>
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Nama Barang</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="namabarang" name="namabarang" value="<?= $namabarang; ?>">
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Pilih kategori</label>
    <div class="col-sm-4">
        <select name="kategori" id="kategori" class="form-control">
            <?php foreach ($datakategori as $kat) : ?>

                <?php if ($kat['katid'] == $kategori) : ?>
                    <option selected value="<?= $kat['katid'] ?>"><?= $kat['katnama'] ?></option>
                <?php else : ?>
                    <option value="<?= $kat['katid'] ?>"><?= $kat['katnama'] ?></option>
                <?php endif; ?>

            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Pilih satuan</label>
    <div class="col-sm-4">
        <select name="satuan" id="satuan" class="form-control">
            <?php foreach ($datasatuan as $sat) : ?>
                <?php if ($sat['satid'] == $satuan) : ?>
                    <option selected value="<?= $sat['satid'] ?>"><?= $sat['satnama'] ?></option>
                <?php else : ?>
                    <option value="<?= $sat['satid'] ?>"><?= $sat['satnama'] ?></option>
                <?php endif; ?>

            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Stok</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="stok" name="stok" value="<?= $stok; ?>">
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Spesifikasi</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="spesifikasi" name="spesifikasi" value="<?= $spesifikasi; ?>">
    </div>
</div>
<div class="form-group-row">
    <label for=""></label>
    <div class="col-sm-4">
        <input type="submit" class="btn btn-success" value="Update Data">
    </div>
</div>
<?= form_close(); ?>
<?= $this->endSection('isi'); ?>