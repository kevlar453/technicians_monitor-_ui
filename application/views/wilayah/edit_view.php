<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>
<script>
    function fetch() {
        var get = document.getElementById("get").value;
        document.getElementById("put").value = get;
    }
</script>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-edit" style="color:green"> </i> Update Wilayah - <?= $wilayah->nama_wilayah; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-edit"></i>&nbsp; Update wilayah - <?= $wilayah->nama_wilayah; ?></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty($this->session->flashdata())) {
                    echo $this->session->flashdata('pesan');
                } ?>

                <div class="box box-primary">
                    <div class="box-header with-border">
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="<?php echo base_url('wilayah/upd'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <input type="hidden" class="form-control" value="<?= $wilayah->id_wil; ?>" name="id_wil">
                                <input type="hidden" name="foto_old" value="<?= $wilayah->foto; ?>">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nama Wilayah</label>
                                        <input type="text" class="form-control" name="nama_wilayah" value="<?= $wilayah->nama_wilayah; ?>" required="required">
                                    </div>
                                    <div class="form-group">
                                        <label>Lambang Wilayah</label>
                                        <input type="file" accept="image/*" name="foto" onchange="readURL(this);">

                                        <br />
                                        <img src="<?php echo 'data:image/png;base64,' . $wilayah->foto; ?>" style="height:auto;width:210px;" class="img-responsive"  id="gambar" alt="#">
                                    </div>
                                    <div class="form-group">
                                        <label>Warna</label>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="warna" id="put" value="<?= $wilayah->warna; ?>" class="form-control">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="color" id="get" onchange="fetch()">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                  <div class="form-group">
                                      <label>Latitude</label>
                                      <input type="text" class="form-control" value="<?= $wilayah->latitude; ?>" name="latitude" required="required">
                                  </div>
                                  <div class="form-group">
                                      <label>Longitude</label>
                                      <input type="text" class="form-control" value="<?= $wilayah->longitude; ?>" name="longitude" required="required">
                                  </div>
                                    <div class="form-group">
                                        <label>GeoJSON</label>
                                        <textarea name="unit_geojson" rows="5" class="form-control" required="required"><?= $wilayah->unit_geojson; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary btn-md">Edit Wilayah</button>
                        </form>
                        <a href="<?= base_url('wilayah'); ?>" class="btn btn-danger btn-md">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
      </section>
    </div>
    <script type="text/javascript">
      function readURL(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();

              reader.onload = function (e) {
                  $('#gambar').attr('src', e.target.result);
              }

              reader.readAsDataURL(input.files[0]);
          }
      }

    </script>
