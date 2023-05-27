<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="<?= base_url() ?>/css/style.css">

</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-wrap p-4 p-md-5">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="fa fa-user-o"></span>
                        </div>
                        <h3 class="text-center mb-4">Sistem Inventory
                            Laboratorium TKJ</h3>

                        <?= form_open('login/cekUser'); ?>
                        <?= csrf_field(); ?>
                        <form action="#" class="login-form">
                            <?php
                            $isInvalidUser = (session()->getFlashdata('errnameuser')) ? 'is-invalid' : '';
                            ?>
                            <div class="form-group">
                                <input type="text" class="form-control rounded-left <?= $isInvalidUser; ?>" name="nameuser" placeholder="Username" autofocus>

                                <?php
                                if (session()->getFlashdata('errnameuser')) {
                                    echo '<div id="validationServer03Feedback" class="invalid-feedback">
                                    ' . session()->getFlashdata('errnameuser') . '
                                    </div>';
                                }
                                ?>
                            </div>

                            <?php
                            $isInvalidPass = (session()->getFlashdata('errPass')) ? 'is-invalid' : '';
                            ?>
                            <div class="form-group">
                                <input type="password" class="form-control rounded-left <?= $isInvalidPass; ?>" name="pass" placeholder="Password">

                                <?php
                                if (session()->getFlashdata('errPass')) {
                                    echo '<div id="validationServer03Feedback" class="invalid-feedback">
                                    ' . session()->getFlashdata('errPass') . '
                                    </div>';
                                }
                                ?>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary rounded submit p-3 px-5">Login</button>
                            </div>
                        </form>
                        <?= form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="<?= base_url() ?>/js/jquery.min.js"></script>
    <script src="<?= base_url() ?>/js/popper.js"></script>
    <script src="<?= base_url() ?>/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>/js/main.js"></script>

</body>

</html>