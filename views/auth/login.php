<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= htmlspecialchars(APP_TITLE) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .login-card {
            max-width: 460px;
            width: 100%;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            background: #ffffff;
            overflow: hidden;
            border: none;
        }
        .login-header {
            background: #ffffff;
            padding: 2rem 2rem 1rem;
            text-align: center;
        }
        .brand-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: #ffffff;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1rem;
            box-shadow: 0 6px 16px rgba(30, 58, 138, 0.3);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="brand-icon">
            <i class="fa-solid fa-shirt"></i>
        </div>
        <h4 class="fw-bold text-dark mb-1">SPK BAHAN BAJU</h4>
        <div class="text-primary fw-semibold small"><?= htmlspecialchars(KONVEKSI_NAME) ?></div>
        <p class="text-muted small mt-2 mb-0">
            Metode Simple Additive Weighting (SAW)<br>
            <strong>Agung Adhyaksa</strong> (NPM: 202243500428)
        </p>
    </div>

    <div class="p-4 pt-2">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 px-3 small d-flex align-items-center mb-3">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success py-2 px-3 small d-flex align-items-center mb-3">
                <i class="fa-solid fa-circle-check me-2"></i>
                <div><?= htmlspecialchars($success) ?></div>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>?page=login" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-user text-muted"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus value="admin">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Kata Sandi</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required value="admin123">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mt-2 shadow-sm">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk ke Sistem
            </button>
        </form>

        <div class="mt-4 p-3 bg-light rounded-3 text-center border">
            <div class="small text-muted fw-semibold mb-1">Akun Demo Pengujian:</div>
            <div class="small text-secondary">Username: <code>admin</code> | Password: <code>admin123</code></div>
            <div class="small text-secondary">Username: <code>pemilik</code> | Password: <code>admin123</code></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

