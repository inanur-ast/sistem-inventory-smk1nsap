<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Edit Data Peminjaman Barang
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<button type="button" class="btn btn-sm btn-primary" onclick="location.href=('/barangpinjam/data')">
    <i class="fa fa-backward"></i> Kembali
</button>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>

<table class="table table-sm table-bordered" style="width: 100%;">
    <tr>
        <td style="width: 20%;">No.Peminjaman</td>
        <td style="width: 2%;">:</td>
        <td style="width: 28%;"><?= $nopeminjam; ?></td>
        </td><input type="hidden" id="nopeminjam" value="<?= $nopeminjam; ?>">
    </tr>
    <tr>
        <td style="width: 20%;">Tanggal Pinjam</td>
        <td style="width: 2%;">:</td>
        <td style="width: 28%;"><?= date('d-m-Y', strtotime($tglpinjam)); ?></td>
    </tr>
    <tr>
        <td style="width: 20%;">Nama Siswa</td>
        <td style="width: 2%;">:</td>
        <td style="width: 28%;"><?= $namasiswa; ?></td>
    </tr>
    </thead>
</table>
<div class="card">
    <div class="card-header bg-primary">
        Pengembalian Barang
    </div>
    <div class="card-body">
        <?= form_open('barangpinjam/cetakBeritaAcara', ['target' => '_blank']); ?>
        <div class="form-row">
            <div class="form-group col-md-3">
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
                <input type="hidden" name="id" id="id">
            </div>
            <div class="form-group col-md-2">
                <label for="">Kategori</label>
                <input type="text" class="form-control" name="kategori" id="kategori" readonly>
            </div>
            <div class="form-group col-md-2">
                <label for="">Jumlah</label>
                <input type="number" class="form-control" name="jumlah" id="jumlah">
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
            <div class="form-group col-md-3">
                <label for=""></label>
                <div class="input-group">
                    <button class="btn btn-sm btn-success" type="button" title="Tambah item" id="tombolTambahItem">
                        <i class="fa fa-plus-square"></i>
                    </button>

                    <button style="display: none;" class="btn btn-sm btn-info" type="button" title="Simpan item" id="tombolSimpanItem">
                        <i class="fa fa-save"></i>
                    </button>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label for="">Tanggal Kembali</label>
                <input type="date" class="form-control" name="tglkembali" id="tglkembali" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="form-group col-md-6">
                <label for=""></label>
                <div class="input-group">
                    <button type="button" class="btn btn-success" id="tombolSelesaiUpdate">
                        Simpan Pengembalian
                    </button>
                </div>
            </div>
        </div>
        <div class="row" id="DataDetail"></div>
    </div>
    <?= form_close(); ?>
</div>
<div class="viewmodal" style="display:none;"></div>

<div class="viewmodalbrg" style="display:none;"></div>

<script>
    function kosong() {
        $('#kdbrg').val('');
        $('#namabrg').val('');
        $('#kategori').val('');
        $('#kondisi').val('');
        $('#jumlah').val('1');
        $('#jumlah').focus();
    }

    function DataDetail() {
        let nopeminjam = $('#nopeminjam').val();
        $.ajax({
            type: "post",
            url: "/barangpinjam/TampilDataDetail",
            data: {
                nopeminjam: nopeminjam
            },
            dataType: "json",
            beforeSend: function() {
                $('#DataDetail').html('<i class="fa fa-spin fa-spinner"></i>')
            },
            success: function(response) {
                if (response.data) {
                    $('#DataDetail').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    function TambahItem() {
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
                url: "/barangpinjam/simpanDetail",
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
                        kosong();
                        DataDetail();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        }
    }


    $(document).ready(function() {
        DataDetail();

        $('#tombolTambahItem').click(function(e) {
            e.preventDefault();
            TambahItem();
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
            let kdbrg = $('#kdbrg').val();
            let kondisi = $('#kondisi').val();
            let jumlah = $('#jumlah').val();

            $.ajax({
                type: "post",
                url: "/barangpinjam/simpanUpdate",
                data: {
                    id: $('#id').val(),
                    kdbrg: kdbrg,
                    kondisi: kondisi,
                    jumlah: jumlah
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.sukses,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                kosong();
                                DataDetail();

                                $('#tombolSimpanItem').hide();
                                $('#tombolTambahItem').fadeIn();
                            }
                        })
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        });


        $('#tombolSelesaiUpdate').click(function(e) {
            e.preventDefault();
            let nopeminjam = $('#nopeminjam').val();
            let tglkembali = $('#tglkembali').val();

            if (tglkembali.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pesan',
                    text: 'Maaf, masukan tanggal pengembalian!',
                });
            } else {
                Swal.fire({
                    title: 'Selesai Peminjaman?',
                    text: "Apakah barang selesai dipinjam?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                    if (result.isConfirmed) {}
                    $.ajax({
                        type: "post",
                        url: "/barangpinjam/SelesaiTransaksiEdit",
                        data: {
                            nopeminjam: nopeminjam,
                            tglkembali: tglkembali,
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