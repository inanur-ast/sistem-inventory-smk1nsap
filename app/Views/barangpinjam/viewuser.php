<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Data Transaksi Barang Pinjam
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
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

<div class="row">
    <div class="col">
        <label>Filter Data</label>
    </div>
    <div class="col">
        <input type="date" name="tglawal" id="tglawal" class="form-control">
    </div>
    <div class="col">
        <input type="date" name="tglakhir" id="tglakhir" class="form-control">
    </div>
    <div class="col">
        <button type="button" class="btn btn-block btn-primary" id="tombolTampilUser">Tampilkan</button>
    </div>
</div>
<br>
<table style="width: 100%; " id="databarangpinjamuser" class="table table-bordered dataTable dtr-inline collapsed">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>No Peminjaman</th>
            <th>Tanggal Pinjam</th>
            <th>Nama siswa</th>
            <th>Jumlah</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<script>
    function listDataBarangPinjam() {
        var table = $('#databarangpinjamuser').DataTable({
            destroy: true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "/barangpinjam/listDataPinjamUser",
                "type": "POST",
                "data": {
                    tglawal: $('#tglawal').val(),
                    tglakhir: $('#tglakhir').val()
                }
            },
            "columnDefs": [{
                "targets": [0, 7],
                "orderable": false,
            }, ],
        });
    }

    $(document).ready(function() {
        listDataBarangPinjam();

        $('#tombolTampilUser').click(function(e) {
            e.preventDefault();
            listDataBarangPinjam();
        });
    });


    function detail(id_peminjam) {
        window.location = ('/barangpinjam/detailpinjam/') + id_peminjam;
    }
</script>
<?= $this->endSection('isi'); ?>