<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus" style="color:green"> </i> Tambah Company
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-plus"></i>&nbsp; Tambah Company</li>
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
                        <form action="<?php echo base_url('master/add'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-6">
                                  <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Nama Perusahaan</label>
                                        <input type="text" autofocus class="form-control" name="nama" required="required" placeholder="Nama Perusahaan" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Kode Perusahaan</label>
                                        <input type="text" autofocus class="form-control" name="kode" onkeyup="genkodeid(this.value);" required="required" placeholder="Harus diisi 6 digit" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Level</label>
                                        <select name="jenis" id="jenis" class="form-control" required="required">
                                            <option value="AC">AC Service</option>
                                            <option value="ATM">ATM Service</option>
                                            <option value="SRV">Multi Service</option>
                                        </select>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-sm-6">
                                  <div class="col-sm-6">
                                    <div class="form-group">
                                      <label>Logo/Lambang Perusahaan</label>
                                      <input type="file" accept="image/*" name="gambar" id="gambar" onchange="readURL(this);">
                                    </div>
                                  </div>
                                  <div class="col-sm-6">
                                    <img src="" style="height:auto;width:210px;" class="img-responsive"  id="pgambar" alt="#">
                                  </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nama Pengguna</label>
                                        <input type="text" autofocus class="form-control" name="usr_nama" required="required" placeholder="Nama Pengguna" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Tempat Lahir</label>
                                        <input type="text" class="form-control" name="usr_lahir" required="required" placeholder="Contoh : Bekasi" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" class="form-control" name="usr_tgl_lahir" required="required" placeholder="Contoh : 1999-05-18" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                      <div class="col-sm-9">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="usr_user" required="required" placeholder="Username" autocomplete="off">
                                      </div>
                                      <div class="col-sm-3">
                                        <label>Kode ID</label>
                                        <input type="text" value="" class="form-control" id="clidentitas" name="usr_clidentitas" readOnly="" placeholder="Kode ID" autocomplete="off">
                                      </div>
                                      <div class="col-sm-12">
                                        <label>Password [default = Kode ID]</label>
                                        <input type="password" value="" class="form-control" id="pass" name="usr_pass" required="required" placeholder="Password">
                                      </div>
                                    </div>
                                    <div class="form-group" style="display:none;">
                                        <label>Level</label>
                                        <select name="usr_level" id="level" class="form-control" required="required">
                                          <option value="manajemen">Manajemen</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                  <div class="row">
                                    <div class="col-sm-4">
                                      <div class="form-group">
                                          <label>Jenis Kelamin</label>
                                          <br />
                                          <input type="radio" name="usr_jenkel" value="Laki-Laki" required="required"> Laki-Laki
                                          <br />
                                          <input type="radio" name="usr_jenkel" value="Perempuan" required="required"> Perempuan
                                      </div>
                                    </div>
                                    <div class="col-sm-4">
                                      <div class="form-group">
                                          <label>Telepon</label>
                                          <input id="uintTextBox" class="form-control" name="telepon" required="required" placeholder="Contoh : 089618173609" autocomplete="off">
                                      </div>
                                    </div>
                                    <div class="col-sm-4">
                                      <div class="form-group">
                                          <label>E-mail</label>
                                          <input type="email" class="form-control" name="usr_email" required="required" placeholder="Contoh : cvbdm123@gmail.com" autocomplete="off">
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <div class="form-group">
                                          <label>Foto Diri</label>
                                          <input type="file" accept="image/*" name="foto" id="foto" onchange="readURL(this);">

                                          <br />
                                          <img src="" style="height:auto;width:210px;" class="img-responsive"  id="pfoto" alt="#">
                                      </div>
                                    </div>
                                    <div class="col-sm-12">
                                      <div class="form-group">
                                          <label>Alamat</label>
                                          <textarea class="form-control" name="usr_alamat" required="required"></textarea>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                              <button type="submit" class="btn btn-primary btn-md">Submit</button>
                              <a href="<?= base_url('master'); ?>" class="btn btn-danger btn-md">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>
</section>
</div>
