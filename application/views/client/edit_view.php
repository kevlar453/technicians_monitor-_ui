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
            <i class="fa fa-edit" style="color:green"> </i> Update unit - <?= $unit->nama_client; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-edit"></i>&nbsp; Update unit - <?= $unit->nama_client; ?></li>
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
                        <form action="<?php echo base_url('unit/upd'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <input type="hidden" class="form-control" value="<?= $unit->id_unit; ?>" name="id_unit">
                                <input type="hidden" name="foto_old" value="<?= $unit->foto; ?>">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nama Unit</label>
                                        <input type="text" class="form-control" name="nama_client" value="<?= $unit->nama_client; ?>" required="required">
                                    </div>
                                    <div class="form-group">
                                        <label>Kepala Unit</label>
                                        <input type="text" class="form-control" readonly name="nama_pic" value="<?= $unit->nama_pic; ?>" required="required">
                                    </div>
                                    <!--id_unit 	nama_client 	nama_pic 	alamat 	serial 	model 	numlabel 	periode 	foto 	unit_geojson 	warna 	latitude 	longitude
                      							-->
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea name="alamat" rows="5" class="form-control" required="required"><?= $unit->alamat; ?></textarea>
                                    </div>
                                    <div class="form-group" id="asal_wilayah">
                                        <label>Wilayah</label>
                                        <select name="asal_wilayah" class="form-control">
                                            <option disabled selected value> -- Pilih Wilayah -- </option>
                                            <?php foreach ($wilayah as $isi) { ?>
                                                <option value="<?= $isi['nama_wilayah']; ?>" <?php if ($isi['nama_wilayah'] == $unit->asal_wilayah) {
                                                                                                echo 'selected';
                                                                                            } ?>><?= $isi['nama_wilayah']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Logo Perusahaan/Foto Klien</label>
                                        <input type="file" accept="image/*" name="gambar" onchange="readURL(this);">

                                        <br />
                                        <img src="<?php echo 'data:image/png;base64,' . $unit->foto; ?>" style="height:auto;width:210px;" class="img-responsive"  id="gambar" alt="#">
                                    </div>
                                    <div class="form-group">
                                        <label>GeoJSON</label>
                                        <textarea name="unit_geojson" rows="5" class="form-control" required="required"><?= $unit->unit_geojson; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Latitude</label>
                                        <input type="text" class="form-control" value="<?= $unit->latitude; ?>" name="latitude" required="required">
                                    </div>
                                    <div class="form-group">
                                        <label>Longitude</label>
                                        <input type="text" class="form-control" value="<?= $unit->longitude; ?>" name="longitude" required="required">
                                    </div>
                                    <div class="form-group">
                                        <label>Warna</label>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="warna" id="put" value="<?= $unit->warna; ?>" class="form-control">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="color" id="get" onchange="fetch()">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary btn-md">Edit Unit</button>
                        </form>
                        <a href="<?= base_url('unit'); ?>" class="btn btn-danger btn-md">Kembali</a>
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
