<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Sistem Pembayaran Tagihan Mahasiswa</title>
  <?= $this->include('base_css') ?>
</head>
<body>
  <?= $this->renderSection('modal') ?>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto"></form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="<?= base_url('assets/img/avatar/avatar-1.png') ?>" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">Hi, <?= session('data')['mahasiswa_nama'] ?? session('role') ?></div></a>
            <div class="dropdown-menu dropdown-menu-right">
              <a href="<?= base_url('auth/process-logout') ?>" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>
      <?= $this->include('sidebar_layout') ?>

      <div class="main-content">
        <?= $this->renderSection('content') ?>
      </div>
      <footer class="main-footer">
        <div class="footer-left">
          Dirancang oleh Ilham Rahmadhani. Template By <a href="https://nauval.in/">Muhamad Nauval Azhar</a> 
        </div>
        <div class="footer-right">
          2.3.0
        </div>
      </footer>
    </div>
  </div>
  <?= $this->include('base_js') ?>
</body>
</html>