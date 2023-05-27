<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Data Inventaris Ruang
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>
<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<?= form_open('barangmasuk/data') ?>

<?= form_close(); ?>
<table class="table table-sm table-bordered">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Nama Ruang Inventaris</th>
            <th>Tanggal</th>
            <th>Lokasi
            <th>Jumlah Item</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($tampildata->getResultArray() as $row) : ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['id']; ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                <td><?= $row['namalokasi']; ?></td>
                <td align="center">
                    <?php
                    $db = \Config\Database::connect();

                    $jumlahData = $db->table('detail_inventarisruang')->where('detinven', $row['id'])->countAllResults();
                    ?>
                    <span style="cursor: pointer; font-weight: bold; color: blue;" onclick="detailItem('<?= $row['id']; ?>')">
                        <?= $jumlahData; ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="viewmodal" style="display: none;"></div>


<script>
    //menampilkan detail jumlah item
    function detailItem(id) {
        $.ajax({
            type: "post",
            url: "/inventarisRuang/detailItem",
            data: {
                id: id
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodal').html(response.data).show();
                    $('#modalitemruang').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }

        });
    }
</script>
<?= $this->endSection('isi'); ?>