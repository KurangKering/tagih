<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="index.html">Stisla</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="index.html">St</a>
    </div>
    <ul class="sidebar-menu">
      <?php $role = session('role'); ?>
      <li>
        <a class="nav-link" href="<?= base_url('/') ?>">
          <i class="far fa-square"></i> <span>Dashboard</span>
        </a>
      </li>

      <?php if ($role == 'pimpinan'): ?>
        <li>
          <a class="nav-link" href="<?= base_url('tagihan/data-pimpinan') ?>">
            <i class="far fa-square"></i> <span>Data Tagihan</span>
          </a>
        </li>
      <?php endif ?>

      <?php if ($role == 'bendahara'): ?>
        <li>
          <a class="nav-link" href="<?= base_url('mahasiswa/data') ?>">
            <i class="far fa-square"></i> <span>Data Mahasiswa</span>
          </a>
        </li>
        <li>
          <a class="nav-link" href="<?= base_url('periode/data') ?>"><i class="far fa-square"></i> <span>Manajemen Periode</span>
          </a>
        </li>
        <li>
          <a class="nav-link" href="<?= base_url('mahasiswa-periode/data') ?>"><i class="far fa-square"></i> <span>Mahasiswa Periode</span>
          </a>
        </li>
        <li>
          <a class="nav-link" href="<?= base_url('tagihan/data-bendahara') ?>"><i class="far fa-square"></i> <span>Data Tagihan</span>
          </a>
        </li>
      <?php endif ?>

      <?php if ($role == 'mahasiswa'): ?>
        <li>
          <a class="nav-link" href="<?= base_url('tagihan/data-mahasiswa') ?>"><i class="far fa-square"></i> <span>Data Tagihan</span>
          </a>
        </li>

      <?php endif ?>

      <?php if ($role == 'pimpinan'): ?>
        <li>
          <a class="nav-link" href="<?= base_url('tagihan/grafik-pimpinan') ?>">
            <i class="far fa-square"></i> <span>Grafik Tagihan</span>
          </a>
        </li>
      <?php endif ?>


    </ul>


  </aside>
</div>