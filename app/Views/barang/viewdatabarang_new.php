<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Manajemen Data Barang
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-plus-circle"></i> Tambah Data', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('barang/formtambah') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>

<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

<!-- DataTables  & Plugins -->
<script src="<?= base_url(); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>


<table class="table table-sm table-bordered" id="databarang" style="width:100%;">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Satuan</th>
            <th>Stok</th>
            <th>Spesifikasi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#databarang').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/barang/listData',
            order: [],
            columns: [{
                    data: 'nomor',
                    orderable: false
                },
                {
                    data: 'brgkode'
                },
                {
                    data: 'brgnama'
                },
                {
                    data: 'katnama'
                },
                {
                    data: 'satnama'
                },
                {
                    data: 'brgstok'
                },
                {
                    data: 'spesifikasi'
                },
                {
                    data: 'aksi'
                }
            ]
        });
    });

    function edit(kode) {
        window.location = ('/barang/formedit/' + kode);
    }

    function hapus(brgkode) {
        Swal.fire({
            title: 'Hapus Transaksi?',
            text: "Yakin menghapus transaksi ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "/barang/hapus",
                    data: {
                        brgkode: brgkode
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.sukses,
                            }).then((result) => {
                                window.location.reload();
                            })
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + '\n' + thrownError);
                    }

                });
            }
        })
    }
</script>
<?= $this->endsection('isi'); ?>