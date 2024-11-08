<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus" style="color:green"> </i> Tambah User
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-plus"></i>&nbsp; Tambah User</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="<?php echo base_url('user/add'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nama Pengguna</label>
                                        <input type="text" autofocus class="form-control" name="nama" required="required" placeholder="Nama Pengguna" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Tempat Lahir</label>
                                        <input type="text" class="form-control" name="lahir" required="required" placeholder="Contoh : Bekasi" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="tgl_lahir" required="required" placeholder="Contoh : 1999-05-18">
                                    </div>
                                    <div class="form-group">
                                      <div class="col-sm-9">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="user" required="required" placeholder="Username" autocomplete="off">
                                      </div>
                                      <div class="col-sm-3">
                                        <label>Kode ID</label>
                                        <input type="text" value="<?= $rdnid; ?>" class="form-control" name="clidentitas" readOnly="" placeholder="Username" autocomplete="off">
                                      </div>
                                      <div class="col-sm-12">
                                        <label>Password [default = Kode ID]</label>
                                        <input type="password" value="<?= $rdnid; ?>" class="form-control" name="pass" required="required" placeholder="Password">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Level</label>
                                        <select name="level" id="level" class="form-control" required="required">
                                          <?php if ($this->session->userdata('level') == 'manajemen') { ?>
                                            <option value="manajemen">Manajemen</option>
                                            <option value="administrator">Administrator</option>
                                          <?php } ?>
                                          <option value="operator">Operator</option>
                                            <option value="technician">Teknisi</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="asal_wilayah">
                                        <label>Wilayah</label>
                                        <select name="asal_wilayah" class="form-control">
                                            <option disabled selected value>-- Pilih Wilayah --</option>
                                            <?php foreach ($wilayah as $isi) { ?>
                                                <option value="<?= $isi['nama_wilayah']; ?>"><?= $isi['nama_wilayah']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Jenis Kelamin</label>
                                        <br />
                                        <input type="radio" name="jenkel" value="Laki-Laki" required="required"> Laki-Laki
                                        <br />
                                        <input type="radio" name="jenkel" value="Perempuan" required="required"> Perempuan
                                    </div>
                                    <div class="form-group">
                                        <label>Telepon</label>
                                        <input id="uintTextBox" class="form-control" name="telepon" required="required" placeholder="Contoh : 089618173609" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>E-mail</label>
                                        <input type="email" class="form-control" name="email" required="required" placeholder="Contoh : happyday@gmail.com" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Foto Diri</label>
                                        <input type="file" accept="image/*" name="gambar" onchange="readURL(this);">

                                        <br />
                                        <img src="" style="height:auto;width:210px;" class="img-responsive"  id="gambar" alt="#">
                                    </div>
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea class="form-control" name="alamat" required="required"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary btn-md">Submit</button>
                        </form>
                        <a href="<?= base_url('user'); ?>" class="btn btn-danger btn-md">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
</div>
</section>
</div>
