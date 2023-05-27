<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Tambah Data Inventaris Ruang
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<button type="button" class="btn btn-sm btn-primary" onclick="location.href=('/inventarisRuang/data')">
    <i class="fa fa-backward"></i> Kembali
</button>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="">Input Nama Ruang Inventaris</label>
        <input type="text" class="form-control" placeholder="nama ruang inventaris" name="inventaris" id="inventaris">
    </div>
    <div class="form-group col-md-4">
        <label for="">Tanggal </label>
        <input type="date" class="form-control" name="tgl" id="tgl" value="<?= date('Y-m-d') ?>">
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary">
        Input Barang
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="">Nama Barang</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Nama barang" name="namabrg" id="namabrg" readonly>
                    <input type="hidden" name="kdbrg" id="kdbrg">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" id="tombolCr" title="Cari barang">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-2">
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
                <label for=""></label>
                <div class="input-group">
                    <button class="btn btn-sm btn-info" type="button" title="Simpan item" id="tombolSimpanItem">
                        <i class="fa fa-save"></i>
                    </button>&nbsp;
                    <button class="btn btn-sm btn-warning" type="button" title="Reload Data" id="tombolReload">
                        <i class="fa fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="">Aksi</label>
                <div class="input-group">
                    <button type="button" class="btn btn-success" id="tombolSelesaiTransaksi">
                        Selesai transaksi
                    </button>
                </div>
            </div>
        </div>
        <div class="row" id="DataTemp"></div>
    </div>
</div>
<div class="viewmodal" style="display:none;"></div>

<div class="viewmodalbrg" style="display:none;"></div>

<script>
    function kosong() {
        $('#kdbrg').val('');
        $('#namabrg').val('');
        $('#kategori').val('');
        $('#jumlah').val('1');
        $('#jumlah').focus();
    }

    function DataTemp() {
        let inventaris = $('#inventaris').val();
        $.ajax({
            type: "post",
            url: "/inventarisRuang/dataTemp",
            data: {
                inventaris: inventaris
            },
            dataType: "json",
            beforeSend: function() {
                $('#DataTemp').html('<i class="fa fa-spin fa-spinner"></i>')
            },
            success: function(response) {
                if (response.data) {
                    $('#DataTemp').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    function simpanItem() {
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
                url: "/inventarisRuang/simpanTemp",
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
                        DataTemp();
                        kosong();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        }
    }


    $(document).ready(function() {
        DataTemp();
        $.ajax({
            url: "<?= base_url('inventarisRuang/auto') ?>",
            type: "GET",
            success: function(hasil) {
                var obj = $.parseJSON(hasil);
                $('#inventaris').val(obj);
            }
        });

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

        $('#tombolSimpanItem').click(function(e) {
            e.preventDefault();
            simpanItem();
        });

        $('#tombolReload').click(function(e) {
            e.preventDefault();
            DataTemp();
        });

        $('#tombolSelesaiTransaksi').click(function(e) {
            e.preventDefault();
            let inventaris = $('#inventaris').val();

            if (inventaris.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pesan',
                    text: 'Maaf nama ruang inventaris tidak boleh kosong!',
                });
            } else {
                Swal.fire({
                    title: 'Selesai transaksi?',
                    text: "Yakin transaksi ini disimpan?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                    if (result.isConfirmed) {}
                    $.ajax({
                        type: "post",
                        url: "/inventarisRuang/SelesaiTransaksi",
                        data: {
                            inventaris: inventaris,
                            tgl: $('#tgl').val(),
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.error,
                                });
                            }
                            if (response.sukses) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.sukses,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.reload();
                                    }
                                })
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + '\n' + thrownError);
                        }
                    });
                });

            }
        });
    });
</script>

<?= $this->endSection('isi'); ?>