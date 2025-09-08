<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon" />
    <title>Login | Sistem Kampus</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: url("<?= base_url('assets/images/bg-campus.jpg') ?>") no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            background: #fff;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 2;
        }

        .login-title {
            font-size: 20px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .form-control {
            padding-left: 40px;
            height: 45px;
            border-radius: 8px;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .btn-login {
            background: linear-gradient(to right, #4164ffff, #a02bffff);
            color: #fff;
            border: none;
            width: 100%;
            padding: 12px;
            font-weight: bold;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        .alert {
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-title">ACCOUNT LOGIN</div>

        <?php if (!empty(session()->getFlashdata('pesan'))) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('pesan') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif ?>

        <form method="post" action="<?= base_url('login') ?>">
            <div class="input-group">
                <i class="fa fa-user input-icon"></i>
                <input type="text" class="<?= ($validation->hasError('username')) ? 'is-invalid' : '' ?> form-control"
                    placeholder="Username" name="username" />
                <div class="invalid-feedback">
                    <?= $validation->getError('username') ?>
                </div>
            </div>

            <div class="input-group">
                <i class="fa fa-lock input-icon"></i>
                <input type="password" class="<?= ($validation->hasError('password')) ? 'is-invalid' : '' ?> form-control"
                    placeholder="Password" name="password" />
                <div class="invalid-feedback">
                    <?= $validation->getError('password') ?>
                </div>
            </div>

            <button type="submit" class="btn-login">LOGIN</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/main.js') ?>"></script>
</body>

</html>