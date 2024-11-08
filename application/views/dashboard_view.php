<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Content Wrapper. Contains page content -->
<!-- Content Header (Page header) -->
<?php if ($this->session->flashdata('success_login')) { ?>
  <script>
    Swal.fire({
      title: 'Success!',
      text: 'Anda Berhasil Login!',
      icon: 'success',
      timer: 1500
    });
  </script>
<?php } ?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Dashboard
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>
  <div>
    <div class="container">

      <?php
      $d = $this->db->query("SELECT * FROM tbl_user WHERE id_user = '$idbo'")->row();
      ?>
      <?php if ($this->session->userdata('level') == 'operator') { ?>
        <section class="content">
          <h2>
            Selamat Datang <?php echo $d->nama;
                            echo ' ( ' . $d->level . ' )'; ?> di Air Conditioner Monitoring System</h2>
          <div class="row">
            <div class="col-lg-6">
              <img src="<?php echo base_url(); ?>assets_style/image/user_1729682000.png" alt="#" class="user-image" style="height:auto;width:100%;" />
            </div>
            <div class="col-lg-5">
              <div class="callout callout-info">
                <h5><i class="fa fa-info"></i>nfo</h5>
                <strong> Air Conditioner Monitoring System (ACS Monit) </strong> Professionally trained and technical staffs and do full support with all brands and all models support major brands.
              </div>
            </div>
          </div>
        </section>
      <?php } elseif ($this->session->userdata('level') == 'administrator') { ?>
        <h2>
          Selamat Datang <?php echo $d->nama;
                          echo ' ( ' . $d->level . ' )'; ?></h2>
      <?php } elseif ($this->session->userdata('level') == 'technician') { ?>
        <h2>
          Selamat Datang <?php echo $d->nama;
                          echo ' ( ' . $d->level . ' ';
                          echo '  ' . $d->asal_wilayah . ' )'; ?></h2>
        <h2>di Air Conditioner Monitoring System</h2>
        <?php } elseif ($this->session->userdata('level') == 'manajemen') { ?>
          <h2>
            Selamat Datang <?php echo $d->nama;
                            echo ' ( ' . $d->level . ' ';
                            echo '  ' . $d->asal_wilayah . ' )'; ?></h2>
        <?php } ?>
    </div>
    <!-- Main content -->
    <?php if ($this->session->userdata('level') == 'administrator' || $this->session->userdata('level') == 'manajemen') { ?>
      <section class="content">
        <div class="row">
          <div class="col-sm-12">
            <div class="col-lg-2 col-xs-6">
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?= $count_pengguna; ?></h3>
                  <p>Pengguna</p>
                </div>
                <div class="icon">
                  <i class="fa fa-user"></i>
                </div>
                <a href="user" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-2 col-xs-6">
              <div class="small-box bg-blue">
                <div class="inner">
                  <h3><?= $count_unit; ?></h3>
                  <p>Klien</p>
                </div>
                <div class="icon">
                  <i class="fa fa-list"></i>
                </div>
                <a href="unit" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <br>
            <div>
              <div class="container-fluid mt-2">
                <div id="map" style="width: 100%; height: 500px;"></div>
              </div>
            </div>
          </div>
      </section>
    <?php } elseif ($this->session->userdata('level') == 'technician') { ?>
      <div class="container-fluid mt-2">
        <div id="map" style="width: 100%; height: 500px;"></div>
      </div>
    <?php } ?>


  </div>
</div>
<!-- /.content -->
