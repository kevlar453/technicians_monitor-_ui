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
            <i class="fa fa-plus" style="color:green"> </i> Tambah Unit
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-plus"></i>&nbsp; Tambah Unit</li>
        </ol>
    </section>
    <section class="content">
        <?php if (!empty($this->session->flashdata())) {
            echo $this->session->flashdata('pesan');
            // 	id_unit 	nama_client 	nama_pic 	alamat 	serial 	model 	numlabel 	periode 	foto 	unit_geojson 	warna 	latitude 	longitude
        } ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="<?php echo base_url('unit/add'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Nama Klien</label>
                                        <input type="text" class="form-control" name="nama_client" required="required" placeholder="Kota ABCDEF" id="nama_client">
                                    </div>
                                    <div class="form-group">
                                        <label>Nama PIC</label>
                                        <input type="text" class="form-control" name="nama_pic" required="required" id="nama_pic">
                                    </div>
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea name="alamat" rows="3" class="form-control" required="required" id="alamat"></textarea>
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
                                    <div class="form-group">
                                        <label>Serial Number</label>
                                        <input type="text" class="form-control" name="serial" required="required" id="serial">
                                    </div>
                                    <div class="form-group">
                                        <label>Model/Version</label>
                                        <input type="text" class="form-control" name="model" required="required" id="model">
                                    </div>
                                    <div class="form-group">
                                        <label>Label Number</label>
                                        <input type="text" class="form-control" name="numlabel" required="required" id="numlabel">
                                    </div>
                                    <div class="form-group">
                                        <label>Periode</label>
                                        <input type="text" class="form-control" name="periode" required="required" id="periode">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Logo Perusahaan/Foto Klien <small style="color:red">[.jpg|.jpeg]* wajib</small></label>
                                        <input type="file" accept="image/*" name="gambar" onchange="readURL(this);">

                                        <br />
                                        <img src="" style="height:auto;width:210px;" class="img-responsive"  id="gambar" alt="#">
                                    </div>
                                    <div class="form-group">
                                        <label>Latitude</label>
                                        <input type="text" class="form-control" name="latitude" required="required" id="latitude">
                                    </div>
                                    <div class="form-group">
                                        <label>Longitude</label>
                                        <input type="text" class="form-control" name="longitude" required="required" id="longitude">
                                    </div>
                                    <div class="form-group">
                                        <label>Warna Pengenal</label>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="warna" id="put" class="form-control">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="color" id="get" onchange="fetch()">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>GeoJSON</label>
                                        <textarea name="unit_geojson" rows="5" class="form-control" required="required" id="geojson"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary btn-md">Submit</button>
                        </form>
                        <a href="<?= base_url('unit'); ?>" class="btn btn-danger btn-md">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
      </section>
    </div>
