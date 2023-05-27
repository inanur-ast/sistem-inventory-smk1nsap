<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Edit Barang Masuk
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<button type="button" class="btn btn-sm btn-primary" onclick="location.href=('/barangmasuk/data')">
    <i class="fa fa-backward"></i> kembali
</button>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>

<table class="table table-sm table-bordered table-hover" style="width: 100%;">
    <tr>
        <td style="width: 20%;">No.Faktur</td>
        <td style="width: 2%;">:</td>
        <td style="width: 28%;"><?= $nofaktur; ?></td>
        <td rowspan="3" style="vertical-align: middle; text-align: center; font-weight: bold; font-size: 20pt;" id="totalHarga">

        </td><input type="hidden" id="faktur" value="<?= $nofaktur; ?>">
    </tr>
    <tr>
        <td style="width: 20%;">Tanggal Faktur</td>
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
                <label for="">Kode Barang</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Input kode barang" name="kdbarang" id="kdbarang">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" id="tombolCariBarang">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="iddetail" id="iddetail">
            </div>
            <div class="form-group col-md-3">
                <label for="">Nama Barang</label>
                <input type="text" class="form-control" name="namabarang" id="namabarang" readonly>
            </div>
            <div class="form-group col-md-2">
                <label for="">Harga Satuan</label>
                <input type="number" class="form-control" name="harga" id="harga">
            </div>
            <div class="form-group col-md-1">
                <label for="">Jumlah</label>
                <input type="number" class="form-control" name="jumlah" id="jumlah">
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
<div class="modalcaribarang" style="display:none;"></div>

<script>
    function dataDetail() {
        let faktur = $('#faktur').val();
        $.ajax({
            type: "post",
            url: "/barangmasuk/dataDetail",
            data: {
                faktur: faktur
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('#tampilDataDetail').html(response.data);
                    $('#totalHarga').html(response.totalharga);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    function kosong() {
        $('#kdbarang').val('');
        $('#namabarang').val('');
        $('#harga').val('');
        $('#jumlah').val('');
        $('#kdbarang').focus();
    }

    //menampilkan data barang yang dicari
    function ambilDataBarang() {
        let kodebarang = $('#kdbarang').val();

        $.ajax({
            type: "post",
            url: "/barangmasuk/ambilDataBarang",
            data: {
                kodebarang: kodebarang
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    let data = response.sukses;
                    $('#namabarang').val(data.namabarang);
                    $('#harga').focus();
                }
                if (response.error) {
                    alert(response.error);
                    kosong();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }


    $(document).ready(function() {
        dataDetail();


        $('#tombolTambahItem').click(function(e) {
            e.preventDefault();
            let faktur = $('#faktur').val();
            let kodebarang = $('#kdbarang').val();
            let harga = $('#harga').val();
            let jumlah = $('#jumlah').val();

            if (faktur.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Maaf faktur tidak boleh kosong!',
                })
            } else if (kodebarang.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Maaf kode barang tidak boleh kosong!',
                })
            } else if (harga.length == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Maaf harga tidak boleh kosong!',
                })
            } else if (jumlah.length == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Maaf jumlah tidak boleh kosong!',
                })
            } else {
                $.ajax({
                    type: "post",
                    url: "/barangmasuk/simpanDetail",
                    data: {
                        faktur: faktur,
                        kodebarang: kodebarang,
                        harga: harga,
                        jumlah: jumlah,
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
        });

        $('#tombolEditItem').click(function(e) {
            e.preventDefault();
            let faktur = $('#faktur').val();
            let kodebarang = $('#kodebarang').val();
            let harga = $('#harga').val();
            let jumlah = $('#jumlah').val();

            $.ajax({
                type: "post",
                url: "/barangmasuk/updateItem",
                data: {
                    iddetail: $('#iddetail').val(),
                    faktur: faktur,
                    kodebarang: kodebarang,
                    harga: harga,
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

        $('#tombolCariBarang').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "/barangmasuk/cariDataBarang",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.modalcaribarang').html(response.data).show();
                        $('#modalcaribarang').modal('show');
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