<div class="modal fade" id="modaltambahsiswa" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Form Input Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open('datasiswa/simpan', ['class' => 'formsimpan']); ?>
                <div class="form-group">
                    <label for="">Nis</label>
                    <input type="text" class="form-control" name="nis" id="nis">
                    <div class="invalid-feedback errornis">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Nama Siswa</label>
                    <input type="text" class="form-control" name="nmsiswa" id="nmsiswa">
                    <div class="invalid-feedback errorNamaSiswa">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Kelas</label>
                    <select name="kelas" id="kelas" class="form-control">
                        <option selected value=" ">Pilih</option>
                        <?php foreach ($datakelas as $k) : ?>
                            <option value="<?= $k['id'] ?>"><?= $k['namakelas'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for=""></label>
                    <button type="submit" class="btn btn-block btn-success" id="tombolsimpan">Simpan</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.formsimpan').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('#tombolsimpan').prop('disabled', true);
                    $('#tombolsimpan').html('<i class="fa fa-spin fa-spinner"></i>')
                },
                complete: function() {
                    $('#tombolsimpan').prop('disabled', false);
                    $('#tombolsimpan').html('Simpan')
                },
                success: function(response) {
                    if (response.error) {
                        let err = response.error;

                        if (err.errnis) {
                            $('#nis').addClass('is-invalid');
                            $('.errornis').html(err.errnis);
                        }
                        if (err.errNamaSiswa) {
                            $('#nmsiswa').addClass('is-invalid');
                            $('.errorNamaSiswa').html(err.errNamaSiswa);
                        }
                        if (err.errKelas) {
                            $('#kelas').addClass('is-invalid');
                            $('.errorKelas').html(err.errKelas);
                        }
                    }
                    if (response.sukses) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.sukses,
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, ambil!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#namasiswa').val(response.namasiswa);
                                $('#nis').val(response.nis);
                                $('#modaltambahsiswa').modal('hide');
                            } else {
                                $('#modaltambahsiswa').modal('hide');
                            }
                        })
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
            return false;
        });
    });
</script>