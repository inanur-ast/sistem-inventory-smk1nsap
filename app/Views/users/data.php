<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Manajemen User
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<button type="button" class="btn btn-sm btn-primary btnTambah">
    <i class="fa fa-plus"></i> Tambah User Baru
</button>
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

<table class="table table-sm table-bordered" id="datauser" style="width:100%;">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Nama Lengkap</th>
            <th>Level</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div class="viewmodal" style="display: none;"></div>
<script>
    $(document).ready(function() {
        $('.btnTambah').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "/users/formTambah",
                success: function(response) {
                    $('.viewmodal').html(response).show();
                    $('#modaltambahuser').on('shown.bs.modal', function(event) {
                        $('#nameuser').focus();
                    })

                    $('#modaltambahuser').modal('show');
                }
            });
        });
        dataUser = $('#datauser').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/users/listData',
            order: [],
            columns: [{
                    data: 'nomor',
                    width: 10
                },
                {
                    data: 'username'
                },
                {
                    data: 'namalengkap'
                },
                {
                    data: 'levelnama'
                },
                {
                    data: 'status',
                    orderable: false,
                    width: 25
                },
                {
                    data: 'aksi',
                    orderable: false,
                    width: 20
                },
            ]
        });
    });

    function view(username) {
        $.ajax({
            type: "post",
            url: "/users/formedit",
            data: {
                username: username
            },
            success: function(response) {
                $('.viewmodal').html(response).show();
                $('#modaledituser').on('shown.bs.modal', function(event) {
                    $('#namalengkap').focus();
                })

                $('#modaledituser').modal('show');
            }
        });
    }
</script>
<?= $this->endsection('isi'); ?>