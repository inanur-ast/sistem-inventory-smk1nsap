<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Edit Data Inventaris Ruang
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<button type="button" class="btn btn-sm btn-primary" onclick="location.href=('/inventarisRuang/data')">
    <i class="fa fa-backward"></i> Kembali
</button>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<table class="table table-sm table-bordered table-hover" style="width: 100%;">
    <tr>
        <td style="width: 20%;">Ruangan</td>
        <td style="width: 2%;">:</td>
        <td style="width: 28%;"><?= $noid; ?></td>
        <td rowspan="3" style="vertical-align: middle; text-align: center; font-weight: bold; font-size: 20pt;" id="totalbarang">

        </td><input type="hidden" id="inventaris" value="<?= $noid; ?>">
    </tr>
    <tr>
        <td style="width: 20%;">Tanggal</td>
        <td style="width: 2%;">:</td>
        <td style="width: 28%;"><?= date('d-m-Y', strtotime($tanggal)); ?></td>
    </tr>
    </thead>
</table>

<div class="card">
    <div class="card-header bg-primary">
        Input Barang
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="">Nama Barang</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Nama barang" name="namabrg" id="namabrg">
                    <input type="hidden" name="kdbrg" id="kdbrg">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" id="tombolCr" title="Cari barang">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="iddetail" id="iddetail">
            </div>
            <div class="form-group col-md-3">
                <label for="">Kategori</label>
                <input type="text" class="form-control" name="kategori" id="kategori" readonly>
            </div>
            <div class="form-group col-md-2">
                <label for="">Jumlah</label>
                <input type="number" class="form-control" name="jumlah" id="jumlah" value="1">
            </div>
            <div class="form-group col-md-2">
                <label for="">Kondisi</label>
                <select name="kondisi" id="kondisi" class="form-control">
                    <option selected value=" ">Pilih</option>
                    <?php foreach ($datakondisi as $k) : ?>
                        <option value="<?= $k['konid'] ?>"><?= $k['konnama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-1">
                <label for="">Aksi</label>
                <div class="input-group">
                    <button class="btn btn-sm btn-info" type="button" title="Tambah item" id="tombolTambahItem">
                        <i class="fa fa-plus-square"></i>
                    </button>

                    <button style="display: none;" class="btn btn-sm btn-primary" type="button" title="Edit item" id="tombolEditItem">
                        <i class="fa fa-edit"></i>
                    </button>&nbsp;
                    <button style="display: none;" class="btn btn-sm btn-secondary" type="button" title="Reload" id="tombolReload">
                        <i class="fa fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="row" id="tampilDataDetail"></div>
    </div>
</div>
<div class="viewmodalbrg" style="display:none;"></div>

<script>
    function kosong() {
        $('#kdbrg').val('');
        $('#namabrg').val('');
        $('#kategori').val('');
        $('#jumlah').val('1');
        $('#jumlah').focus();
    }

    function dataDetail() {
        let inventaris = $('#inventaris').val();
        $.ajax({
            type: "post",
            url: "/inventarisRuang/dataDetail",
            data: {
                inventaris: inventaris
            },
            dataType: "json",
            beforeSend: function() {
                $('#tampilDataDetail').html('<i class="fa fa-spin fa-spinner"></i>')
            },
            success: function(response) {
                if (response.data) {
                    $('#tampilDataDetail').html(response.data);
                    $('#totalbarang').html(response.totalbarang);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    function TambahItem() {
        let inventaris = $('#inventaris').val();
        let kdbrg = $('#kdbrg').val();
        let namabarang = $('namabrg').val();
        let jumlah = $('#jumlah').val();
        let kategori = $('#kategori').val();
        let kondisi = $('#kondisi').val();

        if (kdbrg.length == 0) {
            Swal.fire('Error', 'Kode barang harus diinputkan', 'error');
        } else {
            $.ajax({
                type: "post",
                url: "/inventarisRuang/simpanDetail",
                data: {
                    inventaris: inventaris,
                    kdbrg: kdbrg,
                    namabarang: namabarang,
                    jumlah: jumlah,
                    kategori: kategori,
                    kondisi: kondisi
                },
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.error, 'error');
                        kosong();
                    }
                    if (response.sukses) {
                        Swal.fire('berhasil', response.sukses, 'success');
                        kosong();
                        dataDetail();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        }
    }

    $(document).ready(function() {
        dataDetail();

        $('#tombolCr').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "/inventarisRuang/modalData",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.viewmodalbrg').html(response.data).show();
                        $('#modaldatabrg').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        });

        $('#tombolTambahItem').click(function(e) {
            e.preventDefault();
            TambahItem();
        });

        $('#tombolEditItem').click(function(e) {
            e.preventDefault();
            let kodebarang = $('#kdbrg').val();
            let kondisi = $('#kondisi').val();
            let jumlah = $('#jumlah').val();

            $.ajax({
                type: "post",
                url: "/inventarisRuang/updateItem",
                data: {
                    iddetail: $('#iddetail').val(),
                    kodebarang: kodebarang,
                    kondisi: kondisi,
                    jumlah: jumlah
                },
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.error, 'error');
                        kosong();
                    }
                    if (response.sukses) {
                        Swal.fire('berhasil', response.sukses, 'success');
                        kosong();
                        dataDetail();

                        $('#tombolEditItem').hide();
                        $('#tombolReload').hide();
                        $('#tombolTambahItem').fadeIn();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });

        });


        $('#tombolReload').click(function(e) {
            e.preventDefault();
            $('#iddetail').val('');
            $(this).hide();
            $('#tombolEditItem').hide();
            $('#tombolTambahItem').fadeIn();
            kosong();
        });
    });
</script>
<?= $this->endSection('isi'); ?>