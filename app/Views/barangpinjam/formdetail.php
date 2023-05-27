<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Detail Data Barang Pinjam
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
        <td style="width: 20%;">Tanggal Kembali</td>
        <td style="width: 2%;">:</td>
        <td style="width: 28%;"><?= date('d-m-Y', strtotime($tglkembali)); ?></td>
    </tr>
    <tr>
        <td style="width: 20%;">Nama Siswa</td>
        <td style="width: 2%;">:</td>
        <td style="width: 28%;"><?= $namasiswa; ?></td>
    </tr>
    <tr>
        <td style="width: 20%;">Status</td>
        <td style="width: 2%;">:</td>
        <td style="width: 28%; color: greenyellow; font-style: italic;"><b><?= $status; ?></b></td>
    </tr>
    </thead>
</table>
<div class="card">
    <div class="card-header bg-primary">
        Detail Barang
    </div>
    <div class="card-body">
        <div class="form-row">
        </div>
        <div class="row" id="DataFormDetail"></div>
    </div>
</div>

<script>
    function DataDetail() {
        let nopeminjam = $('#nopeminjam').val();
        $.ajax({
            type: "post",
            url: "/barangpinjam/TampilDataFormDetail",
            data: {
                nopeminjam: nopeminjam
            },
            dataType: "json",
            beforeSend: function() {
                $('#DataDetail').html('<i class="fa fa-spin fa-spinner"></i>')
            },
            success: function(response) {
                if (response.data) {
                    $('#DataFormDetail').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    $(document).ready(function() {
        DataDetail();
    });
</script>
<?= $this->endSection('isi'); ?>