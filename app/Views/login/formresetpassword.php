<?= $this->extend('main/layout'); ?>

<?= $this->section('judul'); ?>
Form Ganti Password
<?= $this->endSection('judul'); ?>

<?= $this->section('subjudul'); ?>

<?= $this->endSection('subjudul'); ?>

<?= $this->section('isi'); ?>
<?= form_open('users/updatepassword', ['class' => 'formupdatepassword']); ?>

<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Password Lama</label>
    <div class="col-sm-6">
        <input type="password" class="form-control" id="passlama" name="passlama" autocomplete="off">
        <div id="msg-passlama" class="invalid-feedback">
        </div>
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Password Baru</label>
    <div class="col-sm-6">
        <input type="password" class="form-control" id="passbaru" name="passbaru">
        <div id="msg-passbaru" class="invalid-feedback">
        </div>
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label">Confirm Password Baru</label>
    <div class="col-sm-6">
        <input type="password" class="form-control" id="confirmpass" name="confirmpass">
        <div id="msg-confirmpass" class="invalid-feedback">
        </div>
    </div>
</div>
<div class="form-group-row">
    <label for=" " class="col-sm-4 col-form-label"></label>
    <div class="col-sm-6">
        <button type="submit" class="btn btn-success btnSimpan">
            Ganti Password
        </button>
    </div>
</div>
<?= form_close(); ?>
<script>
    $(document).ready(function() {
        $('.formupdatepassword').submit(function(e) {
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
                    $('.btnSimpan').html('Ganti Password');
                },
                success: function(response) {
                    if (response.error) {
                        let err = response.error;
                        if (err.passlama) {
                            $('#passlama').addClass('is-invalid');
                            $('#msg-passlama').html(err.passlama);
                        } else {
                            $('#passlama').removeClass('is-invalid');
                            $('#passlama').addClass('is-valid');
                            $('#msg-passlama').html('');
                        }

                        if (err.passbaru) {
                            $('#passbaru').addClass('is-invalid');
                            $('#msg-passbaru').html(err.passbaru);
                        } else {
                            $('#passbaru').removeClass('is-invalid');
                            $('#passbaru').addClass('is-valid');
                            $('#msg-passbaru').html('');
                        }

                        if (err.confirmpass) {
                            $('#confirmpass').addClass('is-invalid');
                            $('#msg-confirmpass').html(err.confirmpass);
                        } else {
                            $('#confirmpass').removeClass('is-invalid');
                            $('#confirmpass').addClass('is-valid');
                            $('#msg-confirmpass').html('');
                        }
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Ganti Password',
                            text: response.sukses,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = '/login/logout';
                            }
                        })
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });
        });
    });
</script>
<?= $this->endSection('isi'); ?>