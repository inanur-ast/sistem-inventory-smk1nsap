<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Tambah Data barang
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<button type="button" class="btn btn-sm btn-secondary" onclick="location.href=('/datapeminjamanbarang/index')">
    <i class="fa fa-backward"></i> Kembali
</button>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<?= form_open('datapeminjamanbarang/simpandata') ?>
<?= session()->getFlashdata('error'); ?>
<?= session()->getFlashdata('sukses'); ?>


<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Kode Barang</label>
    <div class="input-group mb-8 col-sm-6">
        <input type="text" class="form-control" placeholder="Input kode barang" name="kdbarang" id="kdbarang">
        <div class="input-group-append">
            <button class="btn btn-outline-primary" type="button" id="tombolCariBarang">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
</div>

<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Nama Barang</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" id="namabarang" name="namabarang">
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Kategori</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" id="kategori" name="kategori">
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Satuan</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" id="satuan" name="satuan">
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Letak</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" id="letak" name="letak">
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Spesifikasi</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" id="spesifikasi" name="spesifikasi">
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Stok</label>
    <div class="col-sm-6">
        <input type="number" class="form-control" id="stok" name="stok" readonly>
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label"></label>
    <div class="col-sm-6">
        <input type="submit" class="btn btn-success" value="Simpan">
    </div>
</div>
<div class="modalcaripeminjamanbarang" style="display:none;"></div>


<script>
    function ambilDataBarang() {
        let kodebarang = $('#kdbarang').val();

        $.ajax({
            type: "post",
            url: "/datapeminjamanbarang/ambilDataBarang",
            data: {
                kodebarang: kodebarang
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    let data = response.sukses;
                    $('#namabarang').val(data.namabarang);
                    $('#kategori').val(data.kategori);
                    $('#satuan').val(data.satuan);
                    $('#letak').val(data.letak);
                    $('#spesifikasi').val(data.spesifikasi);
                    $('#stok').val(data.stok);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    $(document).ready(function() {
        $('#kdbarang').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                ambilDataBarang();
            }
        });

        $('#tombolCariBarang').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "/datapeminjamanbarang/cariDataBarang",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.modalcaripeminjamanbarang').html(response.data).show();
                        $('#modalcaripeminjamanbarang').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        });
    });
</script>

<?= $this->endSection('isi'); ?>