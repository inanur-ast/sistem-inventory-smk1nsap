<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Tambah Data satuan
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-backward"></i> Kembali', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('satuan/index') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<?= form_open('satuan/simpandata') ?>

<div class="form-group">
    <label for="namasatuan">Nama satuan</label>
    <?= form_input('namasatuan', '', [
        'class' => 'form-control',
        'id' => 'namasatuan',
        'autofocus' => true,
        'placeholder' => 'isikan nama satuan'
    ]) ?>

    <?= session()->getFlashdata('errorNamaSatuan'); ?>
</div>
<div class="form-group">
    <?= form_submit('', 'Simpan', [
        'class' => 'btn btn-success'
    ]) ?>
</div>
<?= form_close(); ?>
<?= $this->endSection('isi'); ?>