<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Edit Data satuan
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-backward"></i> Kembali', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('satuan/index') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<?= form_open('satuan/updatedata', '', [
    'idsatuan' => $id
]) ?>

<div class="form-group">
    <label for="namasatuan">Nama satuan</label>
    <?= form_input('namasatuan', $nama, [
        'class' => 'form-control',
        'id' => 'namasatuan',
        'autofocus' => true,
    ]) ?>

    <?= session()->getFlashdata('errorNamaSatuan'); ?>
</div>
<div class="form-group">
    <?= form_submit('', 'Update', [
        'class' => 'btn btn-success'
    ]) ?>
</div>
<?= form_close(); ?>
<?= $this->endSection('isi'); ?>