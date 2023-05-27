<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Tambah Data Peminjaman Barang
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<button type="button" class="btn btn-sm btn-primary" onclick="location.href=('/barangpinjam/data')">
    <i class="fa fa-backward"></i> Kembali
</button>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<div class="form-row">
    <div class="form-group col-md-4">
        <label for="">Input No. peminjaman</label>
        <input type="text" class="form-control" placeholder="No.peminjaman" name="nopeminjam" id="nopeminjam">
    </div>
    <div class="form-group col-md-4">
        <label for="">Tanggal Pinjam</label>
        <input type="date" class="form-control" name="tglpinjam" id="tglpinjam" value="<?= date('Y-m-d') ?>">
    </div>
    <div class="form-group col-md-4">
        <label for="">Cari Data Siswa</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Nama siswa" name="namasiswa" id="namasiswa" readonly>
            <input type="hidden" name="nis" id="nis">
            <div class="input-group-append">
                <button class="btn btn-outline-primary" type="button" id="tombolCariSiswa" title="Cari Siswa">
                    <i class="fa fa-search"></i>
                </button>
                <button class="btn btn-outline-success" type="button" id="tombolTambahSiswa" title="Tambah Siswa">
                    <i class="fa fa-plus-square"></i>
                </button>
            </div>
        </div>
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
                    </button>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="">Aksi</label>
                <div class="input-group">
                    <button class="btn btn-sm btn-warning" type="button" title="Reload Data" id="tombolReload">
                        <i class="fa fa-sync-alt"></i>
                    </button>&nbsp;
                    <button type="button" class="btn btn-success" id="tombolSelesaiPinjam">
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

    function simpanItem() {
        let nopeminjam = $('#nopeminjam').val();
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
                url: "/barangpinjam/simpanTemp",
                data: {
                    nopeminjam: nopeminjam,
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

    function DataTemp() {
        let nopeminjam = $('#nopeminjam').val();
        $.ajax({
            type: "post",
            url: "/barangpinjam/dataTemp",
            data: {
                nopeminjam: nopeminjam
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


    $(document).ready(function() {
        DataTemp();
        $.ajax({
            url: "<?= base_url('barangpinjam/auto') ?>",
            type: "GET",
            success: function(hasil) {
                var obj = $.parseJSON(hasil);
                $('#nopeminjam').val(obj);
            }
        });

        $('#tombolTambahSiswa').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "/datasiswa/formtambah",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.viewmodal').html(response.data).show();
                        $('#modaltambahsiswa').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });

        });
        $('#tombolCariSiswa').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "/datasiswa/modalData",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.viewmodal').html(response.data).show();
                        $('#modaldatasiswa').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        });


        $('#tombolCr').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "/barangpinjam/modalData",
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

        $('#tombolSelesaiPinjam').click(function(e) {
            e.preventDefault();
            let nopeminjam = $('#nopeminjam').val();
            let nis = $('#nis').val();
            let namasiswa = $('#namasiswa').val();

            if (namasiswa.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pesan',
                    text: 'Maaf nama siswa tidak boleh kosong!',
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
                        url: "/barangpinjam/SelesaiTransaksi",
                        data: {
                            nopeminjam: nopeminjam,
                            tglpinjam: $('#tglpinjam').val(),
                            nis: nis,
                            status: $('#status').val(),
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