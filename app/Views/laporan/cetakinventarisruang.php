<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<button type="button" class="btn btn-sm btn-primary" onclick="location.href=('/laporan/index')">
    <i class="fa fa-backward"></i> kembali
</button>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<div class="row">
    <div class="col">
        <div class="card-header text-gray mb-3" style="background-color: #e2e9ff;">
            Masukkan tanggal untuk mencetak data inventaris ruang :
        </div>
        <div class="card-body">
            <?= form_open('laporan/cetak_inventarisruang_periode', ['target' => '_blank']); ?>
            <div class="row mb-3">
                <label for="tgl_awal" class="col-sm-2 col-form-label">Tanggal Awal</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="tgl_akhir" class="col-sm-2 col-form-label">Tanggal Akhir</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-6">
                    <button type="submit" name="btnCetak" class="btn btn-block btn-success">
                        <i class="fa fa-print"></i> Cetak Laporan
                    </button>
                    <button type="submit" name="btnExport" class="btn btn-block btn-primary">
                        <i class="fa fa-file-excel"></i> Export to Excel
                    </button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>

    </div>
</div>
<?= $this->endSection('isi'); ?>