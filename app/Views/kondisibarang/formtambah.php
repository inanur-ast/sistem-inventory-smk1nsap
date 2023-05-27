<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Tambah Data barang kondisi
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
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
    <label for=" " class="col-sm-4 col-form-label">Stok</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="stok" name="stok">
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Letak</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="letak" name="letak">
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
<script>
    $(document).ready(function() {
        $.ajax({
            url: "<?= base_url('kondisi/auto') ?>",
            type: "GET",
            success: function(hasil) {
                var obj = $.parseJSON(hasil);
                $('#kodebrg').val(obj);
            }
        });
    });
</script>
<?= $this->endSection('isi'); ?>