<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<aside class="main-sidebar skin-green">
  <!-- sidebar: sidebar.less -->
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image">
        <?php
        $d = $this->db->query("SELECT * FROM tbl_user WHERE id_user='$idbo'")->row();
        if (!empty($d->foto !== "-")) {
        ?>
          <br />
          <img src="<?php echo 'data:image/png;base64,' . $d->foto; ?>" alt="#" class="user-image" style="border:2px solid #fff;height:auto;width:100%;" />
        <?php } else { ?>
          <i class="fa fa-user fa-4x" style="color:#fff;"></i>
        <?php } ?>
      </div>
      <div class="pull-left info" style="margin-top: 5px;">
        <p><?php echo $d->nama; ?></p>
        <?php if ($d->level == 'technician') { ?>
          <p><?= $d->level; ?> <?= $d->asal_wilayah; ?>
          </p>
        <?php } else { ?>
          <p>( <?= $d->level; ?> )
          </p>
        <?php } ?>

        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
      <br />
      <br />
      <br />
      <br />
    </div>
    <!-- <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
          <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
          </button>
        </span>
      </div>
    </form> -->
    <ul class="sidebar-menu" data-widget="tree">
      <?php if ($this->session->userdata('level') != 'technician' && $this->session->userdata('level') != 'operator') { ?>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <li class="<?php if ($this->uri->uri_string() == 'dashboard') {
                      echo 'active';
                    } ?>">
          <a href="<?php echo base_url('dashboard'); ?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>

        <?php if ($this->session->userdata('level') == 'manajemen' || $this->session->userdata('level') == 'master') { ?>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-list"></i>
              <span>List</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">

        <?php } ?>


        <li class="<?php if ($this->uri->uri_string() == 'user') {
                      echo 'active';
                    } ?>
        <?php if ($this->uri->uri_string() == 'user/tambah') {
          echo 'active';
        } ?>
        <?php if ($this->uri->uri_string() == 'user/edit/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>">
          <a href="<?php echo base_url('user'); ?>" class="cursor">
            <i class="fa fa-user"></i> <span>Pengguna</span></a>
        </li>

        <?php if ($this->session->userdata('level') == 'master') { ?>
          <li class="<?php if ($this->uri->uri_string() == 'master') {
                        echo 'active';
                      } ?>
          <?php if ($this->uri->uri_string() == 'master/tambah') {
            echo 'active';
          } ?>
          <?php if ($this->uri->uri_string() == 'master/edit/' . $this->uri->segment('3')) {
            echo 'active';
          } ?>">
            <a href="<?php echo base_url('master'); ?>" class="cursor">
              <i class="fa fa-home"></i> <span>Companies</span></a>
          </li>

        <?php } ?>


        <li class="<?php if ($this->uri->uri_string() == 'technician') {
                      echo 'active';
                    } ?>
        <?php if ($this->uri->uri_string() == 'client/tambah') {
          echo 'active';
        } ?>
        <?php if ($this->uri->uri_string() == 'client/edit/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>">
          <a href="<?php echo base_url('client'); ?>" class="cursor">
            <i class="fa fa-database"></i> <span>Klien</span></a>
        </li>

        <li class="<?php if ($this->uri->uri_string() == 'technician') {
                      echo 'active';
                    } ?>
        <?php if ($this->uri->uri_string() == 'wilayah/tambah') {
          echo 'active';
        } ?>
        <?php if ($this->uri->uri_string() == 'wilayah/edit/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>">
          <a href="<?php echo base_url('wilayah'); ?>" class="cursor">
            <i class="fa fa-map"></i> <span>Wilayah</span></a>
        </li>

        <?php if ($this->session->userdata('level') == 'manajemen' || $this->session->userdata('level') == 'master') { ?>
        </li>
        </ul>

        <?php } ?>


      <?php } ?>
      <?php if ($this->session->userdata('level') == 'technician') { ?>
        <li class="<?php if ($this->uri->uri_string() == 'dashboard') {
                      echo 'active';
                    } ?>">
          <a href="<?php echo base_url('dashboard'); ?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="<?php if ($this->uri->uri_string() == 'user/edit/' . $this->uri->segment('3')) {
                      echo 'active';
                    } ?>">
          <a href="<?php echo base_url('user/edit/' . $this->session->userdata('ses_id')); ?>" class="cursor">
            <i class="fa fa-user"></i> <span>Profile</span>
          </a>
        </li>

        <li class="<?php if ($this->uri->uri_string() == 'technician') {
                      echo 'active';
                    } ?>
        <?php if ($this->uri->uri_string() == 'client/tambah') {
          echo 'active';
        } ?>
        <?php if ($this->uri->uri_string() == 'client/edit/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>">
          <a href="<?php echo base_url('client'); ?>" class="cursor">
            <i class="fa fa-database"></i> <span>Klien</span></a>
        </li>

        <li class="treeview
				<?php if ($this->uri->uri_string() == 'panen') {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'panen/tambah') {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'panen/detail/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'panen/edit/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>
        <?php if ($this->uri->uri_string() == 'pemeliharaan') {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'pemeliharaan/tambah') {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'pemeliharaan/detail/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'pemeliharaan/edit/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>
        <?php if ($this->uri->uri_string() == 'jalan') {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'jalan/tambah') {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'jalan/detail/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'jalan/edit/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>
        <?php if ($this->uri->uri_string() == 'pencurian') {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'pencurian/tambah') {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'pencurian/detail/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'pencurian/edit/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>
        <?php if ($this->uri->uri_string() == 'bencana') {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'bencana/tambah') {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'bencana/detail/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>
				<?php if ($this->uri->uri_string() == 'bencana/edit/' . $this->uri->segment('3')) {
          echo 'active';
        } ?>" style="display:none;">
          <a href="#">
            <i class="fa fa-pencil-square"></i>
            <span>Data Geografis</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if ($this->uri->uri_string() == 'panen') {
                          echo 'active';
                        } ?>
						<?php if ($this->uri->uri_string() == 'panen/tambah') {
              echo 'active';
            } ?>
						<?php if ($this->uri->uri_string() == 'panen/detail/' . $this->uri->segment('3')) {
              echo 'active';
            } ?>
						<?php if ($this->uri->uri_string() == 'panen/edit/' . $this->uri->segment('3')) {
              echo 'active';
            } ?>">
              <a href="<?php echo base_url("panen"); ?>" class="cursor">
                <span class="fa fa-tree"></span> Panen
              </a>
            </li>

            <li class="<?php if ($this->uri->uri_string() == 'pemeliharaan') {
                          echo 'active';
                        } ?>
						<?php if ($this->uri->uri_string() == 'pemeliharaan/tambah') {
              echo 'active';
            } ?>
						<?php if ($this->uri->uri_string() == 'pemeliharaan/detail/' . $this->uri->segment('3')) {
              echo 'active';
            } ?>
						<?php if ($this->uri->uri_string() == 'pemeliharaan/edit/' . $this->uri->segment('3')) {
              echo 'active';
            } ?>">
              <a href="<?php echo base_url("pemeliharaan"); ?>" class="cursor">
                <span class="fa fa-leaf"></span> Pemeliharaan
              </a>
            </li>

            <li class="<?php if ($this->uri->uri_string() == 'jalan') {
                          echo 'active';
                        } ?>
						<?php if ($this->uri->uri_string() == 'jalan/tambah') {
              echo 'active';
            } ?>
						<?php if ($this->uri->uri_string() == 'jalan/detail/' . $this->uri->segment('3')) {
              echo 'active';
            } ?>
						<?php if ($this->uri->uri_string() == 'jalan/edit/' . $this->uri->segment('3')) {
              echo 'active';
            } ?>">
              <a href="<?php echo base_url("jalan"); ?>" class="cursor">
                <span class="fa fa-road"></span> Jalan
              </a>
            </li>

            <li class="<?php if ($this->uri->uri_string() == 'pencurian') {
                          echo 'active';
                        } ?>
						<?php if ($this->uri->uri_string() == 'pencurian/tambah') {
              echo 'active';
            } ?>
						<?php if ($this->uri->uri_string() == 'pencurian/detail/' . $this->uri->segment('3')) {
              echo 'active';
            } ?>
						<?php if ($this->uri->uri_string() == 'pencurian/edit/' . $this->uri->segment('3')) {
              echo 'active';
            } ?>">
              <a href="<?php echo base_url("pencurian"); ?>" class="cursor">
                <span class="fa fa-signing"></span> Pencurian
              </a>
            </li>

            <li class="<?php if ($this->uri->uri_string() == 'bencana') {
                          echo 'active';
                        } ?>
						<?php if ($this->uri->uri_string() == 'bencana/tambah') {
              echo 'active';
            } ?>
						<?php if ($this->uri->uri_string() == 'bencana/detail/' . $this->uri->segment('3')) {
              echo 'active';
            } ?>
						<?php if ($this->uri->uri_string() == 'bencana/edit/' . $this->uri->segment('3')) {
              echo 'active';
            } ?>">
              <a href="<?php echo base_url("bencana"); ?>" class="cursor">
                <span class="fa fa-fire"></span> Bencana Alam
              </a>
            </li>

          </ul>
        </li>
        <li class="<?php if ($this->uri->uri_string() == 'laporan') {
                      echo 'active';
                    } ?>" style="display:none;">
          <a href="<?php echo base_url('laporan'); ?>">
            <i class="fa fa-archive"></i> <span>Laporan</span>
          </a>
        </li>
  <?php } ?>
  <?php if ($this->session->userdata('level') == 'manajemen') { ?>
    <li class="<?php if ($this->uri->uri_string() == 'monitor') {
                  echo 'active';
                } ?>">
      <a href="<?php echo base_url('monitor'); ?>">
        <i class="fa fa-globe"></i> <span>Peta</span>
      </a>
    </li>
    <li class="<?php if ($this->uri->uri_string() == 'kehadiran') {
                  echo 'active';
                } ?>">
      <a href="<?php echo base_url('kehadiran'); ?>">
        <i class="fa fa-users"></i> <span>Kehadiran</span>
      </a>
    </li>
    <li class="<?php if ($this->uri->uri_string() == 'laporan') {
                  echo 'active';
                } ?>" style="display:none;">
      <a href="<?php echo base_url('laporan'); ?>">
        <i class="fa fa-archive"></i> <span>Laporan</span>
      </a>
    </li>
  <?php } ?>
  <?php if ($this->session->userdata('level') == 'operator') { ?>
    <li class="<?php if ($this->uri->uri_string() == 'dashboard') {
                  echo 'active';
                } ?>">
      <a href="<?php echo base_url('dashboard'); ?>">
        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
      </a>
    </li>
    <li class="<?php if ($this->uri->uri_string() == 'user/edit/' . $this->uri->segment('3')) {
                  echo 'active';
                } ?>">
      <a href="<?php echo base_url('user/edit/' . $this->session->userdata('ses_id')); ?>" class="cursor">
        <i class="fa fa-user"></i> <span>Profile</span>
      </a>
    </li>
    <li class="<?php if ($this->uri->uri_string() == 'monitor') {
                  echo 'active';
                } ?>">
      <a href="<?php echo base_url('monitor'); ?>">
        <i class="fa fa-map"></i> <span>Peta</span>
      </a>
    </li>
    <li class="<?php if ($this->uri->uri_string() == 'kehadiran') {
                  echo 'active';
                } ?>">
      <a href="<?php echo base_url('kehadiran'); ?>">
        <i class="fa fa-archive"></i> <span>Kehadiran</span>
      </a>
    </li>
    <li class="<?php if ($this->uri->uri_string() == 'jadwal') {
                  echo 'active';
                } ?>">
      <a href="<?php echo base_url('jadwal'); ?>">
        <i class="fa fa-archive"></i> <span>Jadwal</span>
      </a>
    </li>
    <li class="<?php if ($this->uri->uri_string() == 'laporan') {
                  echo 'active';
                } ?>" style="display:none;">
      <a href="<?php echo base_url('laporan'); ?>">
        <i class="fa fa-archive"></i> <span>Laporan</span>
      </a>
    </li>
  <?php } ?>
  </ul>


  <div class="clearfix"></div>
  <br />
  <br />
  </section>
  <!-- /.sidebar -->
</aside>
