<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Tambah Data barang
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-backward"></i> Kembali', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('barang/index') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<?= form_open('barang/simpandata') ?>
<?= session()->getFlashdata('error'); ?>
<?= session()->getFlashdata('sukses'); ?>

<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Kode Barang</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="kodebrg" name="kodebrg" readonly>
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Nama Barang</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="namabarang" name="namabarang">
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Pilih kategori</label>
    <div class="col-sm-4">
        <select name="kategori" id="kategori" class="form-control">
            <option selected value=" ">Pilih</option>
            <?php foreach ($datakategori as $kat) : ?>
                <option value="<?= $kat['katid'] ?>"><?= $kat['katnama'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Pilih satuan</label>
    <div class="col-sm-4">
        <select name="satuan" id="satuan" class="form-control">
            <option selected value=" ">Pilih</option>
            <?php foreach ($datasatuan as $sat) : ?>
                <option value="<?= $sat['satid'] ?>"><?= $sat['satnama'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Stok</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="stok" name="stok">
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Spesifikasi</label>
    <div class="col-sm-4">
        <input type="text-area" class="form-control" id="spesifikasi" name="spesifikasi">
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label"></label>
    <div class="col-sm-4">
        <input type="submit" class="btn btn-success" value="Simpan">
    </div>
</div>

<?= form_close(); ?>

<script>
    $(document).ready(function() {
        $.ajax({
            url: "<?= base_url('barang/auto') ?>",
            type: "GET",
            success: function(hasil) {
                var obj = $.parseJSON(hasil);
                $('#kodebrg').val(obj);
            }
        });
    });
</script>

<?= $this->endSection('isi'); ?>