<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Manajemen Data Kategori
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<?= form_button('', '<i class="fa fa-plus-circle"></i> Tambah Data', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('kategori/formtambah') . "')"
]) ?>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<?= session()->getFlashdata('sukses'); ?>

<table class="table table-striped table-bordered" style="width:100%;">
    <thead class="table-dark">
        <tr>
            <th style="width: 10%;">No</th>
            <th>Nama Kategori</th>
            <th style="width: 15%;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($tampildata as $row) : ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['katnama']; ?></td>
                <td>
                    <button type="button" class="btn btn-info" title="Edit data" onclick="edit('<?= $row['katid']; ?>')">
                        <i class="fa fa-edit"></i>
                    </button>
                    <form method="POST" action="/kategori/hapus/<?= $row['katid'] ?>" style="display:inline;" onsubmit="hapus();">
                        <input type="hidden" value="DELETE" name="_method">
                        <button type="submit" class="btn btn-danger btn-circle" title="Hapus data">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>

                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<script>
    function edit(id) {
        window.location = ('/kategori/formedit/' + id);
    }

    function hapus(id) {
        pesan = confirm('Yakin data kategori dihapus?');
        if (pesan) {
            window.location = ('/kategori/hapus/' + id);
        } else {
            return false;
        }
    }
</script>

<?= $this->endSection('isi'); ?>