<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

<div class="modal fade" id="modaledituser" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Form View User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('users/update', ['class' => 'formsimpan']); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Username</label>
                    <input type="text" class="form-control form-control-sm" name="nameuser" id="nameuser" autocomplete="off" value="<?= $nameuser; ?>" readonly="true">

                </div>
                <div class="form-group">
                    <label for="">Nama Lengkap</label>
                    <input type="text" class="form-control form-control-sm" name="namalengkap" id="namalengkap" autocomplete="off" value="<?= $namalengkap; ?>">
                </div>
                <div class="form-group">
                    <label for="">Level User : </label>
                    <select name="level" id="level" class="form-control form-control-sm" autocomplete="off">
                        <?php foreach ($datalevel->getResultArray()  as $x) : ?>

                            <?php if ($level == $x['levelid']) : ?>
                                <option selected value="<?= $x['levelid']; ?>"><?= $x['levelnama']; ?></option>
                            <?php else : ?>
                                <option value="<?= $x['levelid']; ?>"><?= $x['levelnama']; ?></option>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Status User :</label>
                    <input type="checkbox" <?= ($status == '1') ? 'checked' : ''; ?> data-toggle="toggle" data-on="Active" data-off="Not Active" data-onstyle="success" data-offstyle="danger" data-width="150" data-size="xs" class="chStatus">
                </div>
                <div class="form-group viewResetPassword" style="display: none;">
                    <label for="">Password Baru Anda :</label>
                    <br>
                    <h3 class="passwordReset"></h3>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn bg-purple btnReset"><i class="fa fa-recycle"> Reset password </i></button>
                    <button type="submit" class="btn btn-danger btnHapus"><i class="fa fa-trash-alt"></i> Hapus </button>
                    <button type="submit" class="btn btn-success btnSimpan">Update</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.btnReset').click(function(e) {
            e.preventDefault();
            let nameuser = $('#nameuser').val();
            Swal.fire({
                title: 'Reset Password?',
                html: `Yakin nameuser <strong>${nameuser}</strong ini di Reset Passwordnya?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Reset!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "/users/resetpassword",
                        data: {
                            nameuser: nameuser
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.sukses == '') {
                                $('.viewResetPassword').show();
                                $('.passwordReset').html(response.passwordBaru);
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + '\n' + thrownError);
                        }

                    });
                }
            })

        });

        $('.btnHapus').click(function(e) {
            e.preventDefault();
            let nameuser = $('#nameuser').val();
            Swal.fire({
                title: 'Hapus User?',
                html: `Yakin nameuser <strong>${nameuser}</strong ini dihapus?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "/users/hapus",
                        data: {
                            nameuser: nameuser
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.sukses) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'berhasil',
                                    text: response.sukses,
                                });
                                dataUser.ajax.reload();
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + '\n' + thrownError);
                        }

                    });
                }
            })

        });

        $('.chStatus').change(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "/users/updateStatus",
                data: {
                    nameuser: $('#nameuser').val()
                },
                dataType: "dataType",
                success: function(response) {
                    if (response.sukses == '') {
                        $('#modaledituser').modal('hide');
                        dataUser.ajax.reload();
                    }
                }
            });
        });
        $('.formsimpan').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                cache: false,
                beforeSend: function() {
                    $('.btnSimpan').prop('disabled', true);
                    $('.btnSimpan').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                complete: function() {
                    $('.btnSimpan').prop('disabled', true);
                    $('.btnSimpan').html('Update');
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'berhasil',
                        text: response.sukses,
                    });
                    $('#modaledituser').modal('hide');
                    dataUser.ajax.reload();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        });
    });
</script>