<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Cetak Laporan
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>

<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>

<div class="row">
    <div class="col-lg-4">
        <div class="card border-left-primary shadow h-60 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <button type="button" class="btn btn-block btn-lg" onclick="window.location=('/laporan/cetak_barang_masuk')">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Laporan Barang Masuk
                            </div>
                        </button>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file fa-3x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-left-primary shadow h-60 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <button type="button" class="btn btn-block btn-lg" onclick="window.location=('/laporan/cetak_barang_pinjam')">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Laporan Barang Pinjam
                            </div>
                        </button>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file fa-3x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-left-primary shadow h-60 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <button type="button" class="btn btn-block btn-lg" onclick="window.location=('/laporan/cetak_barang')">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Laporan Barang
                            </div>
                        </button>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file fa-3x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-left-primary shadow h-60 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <button type="button" name="btnCetak" class="btn btn-block btn-lg" onclick="window.location=('/laporan/cetak_inventaris_ruang')">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Laporan Inventaris Ruang
                            </div>
                        </button>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file fa-3x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('isi'); ?>