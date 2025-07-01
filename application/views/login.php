<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Aplikasi Stok Barang123</title>
    <link rel="shortcut icon" href="<?= base_url('assets/img/favicon.ico'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css'); ?>">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f7fc;
            color: #333;
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-card img {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
        }

        .login-card h3 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #555;
        }

        .btn-warning {
            background: #5d8bf4;
            border-color: #5d8bf4;
        }

        .btn-warning:hover {
            background: #4b79d1;
            border-color: #4b79d1;
        }

        .form-group label {
            font-size: 14px;
            color: #555;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <img src="<?= base_url('assets/img/login.png'); ?>" alt="Logo">
            <h3>Aplikasi Stok Barang</h3>

            <?php if ($this->session->flashdata('alert')): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('alert'); ?>
                </div>
            <?php endif; ?>

            <?= form_open(); ?>

            <div class="form-group">
                <label for="username"><i class="fa fa-user"></i> Username</label>
                <input type="text" class="form-control form-control-sm <?= (form_error('username')) ? 'is-invalid' : ''; ?>"
                       name="username" id="username" placeholder="Username" autocomplete="off" value="<?= set_value('username'); ?>">
                <div class="invalid-feedback">
                    <?= form_error('username', '<p>', '</p>'); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="password"><i class="fa fa-lock"></i> Password</label>
                <input type="password" class="form-control form-control-sm <?= (form_error('password')) ? 'is-invalid' : ''; ?>"
                       id="password" placeholder="Password" autocomplete="off" name="password">
                <div class="invalid-feedback">
                    <?= form_error('password', '<p>', '</p>'); ?>
                </div>
            </div>

            <button type="submit" name="submit" value="submit" class="btn btn-warning btn-block text-white btn-sm">
                Sign In <i class="fa fa-sign-in"></i>
            </button>

            <?= form_close(); ?>

            <div class="footer">
                Copyright &copy; <script>document.write(new Date().getFullYear());</script> All rights reserved
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/popper.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
</body>
</html>
