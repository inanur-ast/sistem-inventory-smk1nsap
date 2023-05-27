<div class="modal fade" id="modaltambahuser" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Form Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('users/simpan', ['class' => 'formsimpan']); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Username</label>
                    <input type="text" class="form-control form-control-sm" name="nameuser" id="nameuser" autocomplete="off">
                    <div id="msg-nameuser" class="invalid-feedback">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Nama Lengkap</label>
                    <input type="text" class="form-control form-control-sm" name="namalengkap" id="namalengkap" autocomplete="off">
                    <div id="msg-namalengkap" class="invalid-feedback">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Level User : </label>
                    <select name="level" id="level" class="form-control form-control-sm" autocomplete="off">
                        <option value="" selected>-Pilih-</option>
                        <?php foreach ($datalevel->getResultArray()  as $x) : ?>
                            <option value="<?= $x['levelid']; ?>"><?= $x['levelnama']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="msg-level" class="invalid-feedback">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-block btn-success btnSimpan">Simpan</button>
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
                cache: false,
                beforeSend: function() {
                    $('.btnSimpan').prop('disabled', false);
                    $('.btnSimpan').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                complete: function() {
                    $('.btnSimpan').prop('disabled', false);
                    $('.btnSimpan').html('Simpan');
                },
                success: function(response) {
                    if (response.error) {
                        let err = response.error;
                        if (err.nameuser) {
                            $('#nameuser').addClass('is-invalid');
                            $('#msg-nameuser').html(err.nameuser);
                        } else {
                            $('#nameuser').removeClass('is-invalid');
                            $('#nameuser').addClass('is-valid');
                            $('#msg-nameuser').html('');
                        }
                        if (err.namalengkap) {
                            $('#namalengkap').addClass('is-invalid');
                            $('#msg-namalengkap').html(err.namalengkap);
                        }
                        if (err.level) {
                            $('#level').addClass('is-invalid');
                            $('#msg-level').html(err.level);
                        }
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'berhasil',
                            text: response.sukses,
                        });
                        $('#modaltambahuser').modal('hide');
                        dataUser.ajax.reload();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        });
    });
</script>